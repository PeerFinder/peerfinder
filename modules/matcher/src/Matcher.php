<?php

namespace Matcher;

use App\Helpers\Facades\Urler;
use App\Models\User;
use App\Rules\ConfirmCheckbox;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Matcher\Events\MemberJoinedPeergroup;
use Matcher\Events\MemberLeftPeergroup;
use Matcher\Events\PeergroupCreated;
use Matcher\Events\PeergroupDeleted;
use Matcher\Events\UserApproved;
use Matcher\Events\UserRequestedToJoin;
use Matcher\Exceptions\MembershipException;
use Matcher\Models\Bookmark;
use Matcher\Models\Peergroup;
use Matcher\Models\Language;
use Matcher\Models\Membership;
use Matcher\Rules\IsGroupMember;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Matcher\Models\Appointment;
use Matcher\Models\GroupType;
use Matcher\Models\Tag;
use App\Helpers\Facades\EasyDate;
use Illuminate\Support\Facades\Response;
use Matcher\Events\InvitationSent;
use Matcher\Models\Invitation;
use DateTimeZone;

class Matcher
{
    private $filters = [
        'language' => [],
        'groupType' => [],
        'virtual' => [],
        'tag' => [],
        'search' => [],
    ];

    public function __construct()
    {
        $this->user = auth()->user();

        $this->min_upload_width = config('matcher.image.min_upload_width');
        $this->min_upload_height = config('matcher.image.min_upload_height');
        $this->max_upload_file = config('matcher.image.max_upload_file');

        $this->locale = app()->getLocale();
    }

    public function cleanupForUser(User $user)
    {
        $user->peergroups()->each(function ($pg) {
            $pg->delete();
        });

        $user->memberships()->each(function ($membership) {
            $membership->delete();
        });

        $user->received_invitations()->each(function ($invitation) {
            $invitation->delete();
        });

        $user->sent_invitations()->each(function ($invitation) {
            $invitation->delete();
        });
    }

    public function storePeergroupData($pg, Request $request)
    {
        $input = $request->all();

        Validator::make($input, Peergroup::rules()[$pg ? 'update' : 'create'])->validate();

        $groupType = GroupType::whereIdentifier($request->group_type)->first();

        if ($pg) {
            $pg->fill($input);
        } else {
            $pg = new Peergroup($input);
            $pg->user()->associate($request->user());
            $pg->open = true;
        }

        $pg->groupType()->associate($groupType);
        $pg->save();

        $languages = Language::whereIn('code', array_values($request->languages))->get();
        $pg->languages()->sync($languages);

        if ($request->has('search_tags')) {
            $pg->syncTags($request->search_tags);
        } else {
            $pg->syncTags([]);
        }

        return $pg;
    }

    public function completeGroup(Peergroup $pg, Request $request)
    {
        $input = $request->all();

        Validator::make($input, ['status' => ['required', 'boolean']])->validate();

        if ($input['status'] === '1') {
            $pg->complete();
            return back()->with('success', __('matcher::peergroup.peergroup_was_completed'));
        } else {
            if ($pg->canUncomplete()) {
                $pg->uncomplete();
                return back()->with('success', __('matcher::peergroup.peergroup_was_uncompleted'));
            } else {
                return back()->withErrors(__('matcher::peergroup.peergroup_cannot_be_uncompleted'));
            }
        }
    }

    public function deleteGroup(Peergroup $pg, Request $request)
    {
        $input = $request->all();

        Validator::make($input, ['confirm_delete' => [
            'required',
            'boolean',
            new ConfirmCheckbox(__('matcher::peergroup.delete_group_confirm_validation_error')),
        ]])->validate();

        $pg->delete();
    }

    public function changeOwner(Peergroup $pg, Request $request)
    {
        $input = $request->all();

        Validator::make($input, ['owner' => [
            'required',
            'exists:users,username',
            new IsGroupMember($pg, __('matcher::peergroup.change_owner_validation_error')),
        ]])->validate();

        $pg->setOwner(User::where('username', $input['owner'])->first());
    }

    public function canUserJoinGroup(Peergroup $pg, User $user)
    {
        # User is already a member? Nothing to do
        if ($pg->isMember($user, true)) {
            throw new MembershipException(__('matcher::peergroup.exception_user_already_member', ['user' => $user->name]));
        }

        # If the group is full, nobody can join
        if ($pg->isFull()) {
            throw new MembershipException(__('matcher::peergroup.exception_limit_is_reached'));
        }

        # User without invitation cannot join private groups. Owners can.
        if (!$pg->allowedToJoin($user)) {
            throw new MembershipException(__('matcher::peergroup.exception_cannot_join_private_group'));
        }

        # If the group is marked as completed/closed only invited users can join
        if (!$pg->isOpen() && !$pg->userHasInvitation($user)) {
            throw new MembershipException(__('matcher::peergroup.exception_group_is_completed'));
        }
    }

    public function addMemberToGroup(Peergroup $pg, User $user, $input = [])
    {
        $this->canUserJoinGroup($pg, $user);

        Validator::make($input, Membership::rules()['create'])->validate();

        $membership = new Membership();
        $membership->peergroup_id = $pg->id;
        $membership->user_id = $user->id;

        if ($pg->needsApproval($user)) {
            $membership->approved = false;
        } else {
            $membership->approved = true;
        }

        $membership->fill($input);

        $membership->save();

        $pg->updateStates();

        return $membership;
    }

    public function addOwnerAsMemberToGroup(Peergroup $pg)
    {
        $input = [];

        $membership = $this->addMemberToGroup($pg, $pg->user, $input);

        return $membership;
    }

    public function removeMemberFromGroup(Peergroup $pg, User $user)
    {
        Membership::where(['peergroup_id' => $pg->id, 'user_id' => $user->id])->first()->delete();
    }

    public function getPendingMemberships(Peergroup $pg)
    {
        $memberships = Membership::where(['peergroup_id' => $pg->id, 'approved' => false])->with('user')->get()->all();

        return $memberships;
    }

    public function approveMember(Peergroup $pg, User $user)
    {
        if ($pg->isFull()) {
            throw new MembershipException(__('matcher::peergroup.exception_limit_is_reached'));
        }

        $membership = Membership::where(['peergroup_id' => $pg->id, 'user_id' => $user->id])->firstOrFail();

        $membership->approve();
    }

    public function afterPeergroupCreated(Peergroup $pg)
    {
        PeergroupCreated::dispatch($pg);
    }

    public function beforePeergroupDeleted(Peergroup $pg)
    {
        PeergroupDeleted::dispatch($pg);
    }

    public function afterMemberAdded(Peergroup $pg, User $user, Membership $membership)
    {
        $this->deleteInvitations($pg, $user);

        MemberJoinedPeergroup::dispatch($pg, $user, $membership);
    }

    public function afterUserApproved(Peergroup $pg, User $user, Membership $membership)
    {
        UserApproved::dispatch($pg, $user, $membership);
    }

    public function afterUserRequestedToJoin(Peergroup $pg, User $user, Membership $membership)
    {
        UserRequestedToJoin::dispatch($pg, $user, $membership);
    }

    public function beforeMemberRemoved(Peergroup $pg, User $user)
    {
        MemberLeftPeergroup::dispatch($pg, $user);
    }

    public function afterInvitationCreated(Peergroup $pg, User $receiver, User $sender, Invitation $invitation)
    {
        InvitationSent::dispatch($pg, $receiver, $sender, $invitation);
    }

    public function updateBookmarks(Peergroup $pg, Request $request)
    {
        $input = $request->input();

        Validator::make($input, Bookmark::rules()['update'], [
            'url.*.*' => __('matcher::peergroup.value_must_be_url'),
            'title.*.*' => __('matcher::peergroup.title_too_long'),
        ])->validate();

        if (key_exists('url', $input) && key_exists('title', $input)) {
            # Prevent index error in the bookmark loop
            $count = min(count($input['url']), count($input['title']));
        } else {
            $count = 0;
        }

        Bookmark::where('peergroup_id', $pg->id)->delete();

        for ($i = 0; $i < $count; $i++) {
            $bookmark = [
                'peergroup_id' => $pg->id,
                'url' => $input['url'][$i],
                'title' => $input['title'][$i],
            ];

            Bookmark::create($bookmark);
        }
    }

    public function renderMarkdown($raw_text)
    {
        $html = Str::of($raw_text)->markdown([
            'html_input' => 'strip',
            'renderer' => [
                'soft_break' => "<br>\n",
            ]
        ]);

        $html = trim($html);

        return $html;
    }

    public function storeGroupImage(Peergroup $pg, Request $request)
    {
        $request->validate([
            'image' => sprintf(
                'required|image|max:%d|dimensions:min_width=%d,min_height=%d',
                $this->max_upload_file,
                $this->min_upload_width,
                $this->min_upload_height,
            ),
        ]);

        $image = Image::make($request->file('image'))->orientate();

        $this->saveImageForPeergroup($pg, $image);
    }

    public function saveImageForPeergroup(Peergroup $pg, \Intervention\Image\Image $image)
    {
        if ($pg->image) {
            $newFileName = $pg->image;
        } else {
            $newFileName = Str::uuid() . '.jpg';
        }

        $fitted_image = $image->fit($this->min_upload_width, $this->min_upload_height);

        Storage::disk('public')->put('matcher/images/' . $newFileName, (string) $fitted_image->encode('jpg'));

        $small_image = $image->resize($this->min_upload_width / 2, $this->min_upload_height / 2);

        Storage::disk('public')->put('matcher/images/thumbnails/' . $newFileName, (string) $small_image->encode('jpg'));

        $pg->image = $newFileName;

        $pg->save();
    }

    public function removeGroupImage(Peergroup $pg, Request $request)
    {
        if ($pg->image) {
            Storage::disk('public')->delete('matcher/images/' . $pg->image);
            Storage::disk('public')->delete('matcher/images/thumbnails/' . $pg->image);
            $pg->image = null;
            $pg->save();
        }
    }

    public function getGroupImageLink(Peergroup $pg, $thumbnail = false)
    {
        if ($pg->image == null) {
            $this->generatePlaceholderImage($pg);
        }

        return Urler::versioned_asset('/storage' . ($thumbnail ? '/matcher/images/thumbnails/' : '/matcher/images/') . $pg->image);
    }

    public function generatePlaceholderImage(Peergroup $pg)
    {
        $colors = [
            'EF919B', 'F8B392', 'A0CFA2', '71BEE7', '8380B3', 'D6BAD4', '9796BC', '9CBFCF', 'FFCDAB', 'CBD9BF', 'BAD8FF', 'FFCFCF',
        ];

        $color = '#' . $colors[rand(0, count($colors) - 1)];

        $image = Image::canvas($this->min_upload_width, $this->min_upload_height, $color);

        $overlay = Image::make(resource_path('images/placeholders/group.png'));

        $overlay->resize($image->width(), $image->height());

        $image->insert($overlay, 'center');

        $this->saveImageForPeergroup($pg, $image);
    }

    function groupTypesSelect($type = null, $level = 0)
    {
        $title_field = 'title_' . $this->locale;

        if ($type) {
            $options = [];

            $sub_types = $type->groupTypes;

            $options[$type->identifier] = trim(str_repeat('-', $level - 1) . ' ' . $type->$title_field);
        } else {
            $options = ['' => ''];

            $sub_types = GroupType::where('group_type_id', null)
                ->with('groupTypes')
                ->orderBy('title_' . $this->locale)
                ->get();
        }

        $sub_types->each(function ($el) use (&$options, $level) {
            $options = array_merge($options, $this->groupTypesSelect($el, $level + 1));
        });

        return $options;
    }

    public function generateFilters($peergroups)
    {
        $filters = $this->filters;

        $urlParams = array_filter(request()->query());

        unset($urlParams['page']);

        $peergroups->each(function ($pg) use (&$filters) {
            # Collect languages
            $pg->languages->each(function ($lang) use (&$filters) {
                if (!key_exists($lang->code, $filters['language'])) {
                    $filters['language'][$lang->code] = ['title' => $lang->title, 'count' => 1, 'param' => $lang->code];
                } else {
                    $filters['language'][$lang->code]['count']++;
                }
            });

            # Collect group types
            if ($pg->groupType) {
                if (!key_exists($pg->groupType->identifier, $filters['groupType'])) {
                    $filters['groupType'][$pg->groupType->identifier] = ['title' => $pg->groupType->title(), 'count' => 1, 'param' => $pg->groupType->identifier];
                } else {
                    $filters['groupType'][$pg->groupType->identifier]['count']++;
                }
            }

            # Collect virtual or not
            $virtual = $pg->virtual ? 'yes' : 'no';
            if (!key_exists($virtual, $filters['virtual'])) {
                $filters['virtual'][$virtual] = ['title' => __('matcher::peergroup.bool_' . $virtual), 'count' => 1, 'param' => $virtual];
            } else {
                $filters['virtual'][$virtual]['count']++;
            }

            # Collect tags
            $pg->tags->each(function ($tag) use (&$filters) {
                if (!key_exists($tag->slug, $filters['tag'])) {
                    $filters['tag'][$tag->slug] = ['title' => $tag->name, 'count' => 1, 'param' => $tag->name];
                } else {
                    $filters['tag'][$tag->slug]['count']++;
                }
            });
        });

        foreach ($filters as $key => &$filter) {
            $urlParamsCopy = $urlParams;

            usort($filter, function ($a, $b) {
                return strcmp(strtolower($a['title']), strtolower($b['title']));
            });

            foreach ($filter as &$f) {
                $urlParamsCopy[$key] = $f['param'];
                $f['link'] = route('matcher.index', $urlParamsCopy);
                $f['active'] = key_exists($key, $urlParams) && $urlParams[$key] == $f['param'];
            }
        }

        return $filters;
    }

    public function getFilteredPeergroups(Request $request)
    {
        $filters = $this->filters;

        foreach ($filters as $filter_key => &$value) {
            if ($request->get($filter_key)) {
                $value = $request->get($filter_key);
            } else {
                unset($filters[$filter_key]);
            }
        }

        $cache_key = 'peergroups?' . http_build_query($filters);

        # Cache all peergroups here for 60 seconds. TODO for the future: remove the cache
        # if some peergroup was updated.
        $peergroups = cache()->remember($cache_key, 1, function () use ($request) {
            $query = Peergroup::withDefaults()->whereOpen(true)->wherePrivate(false);

            if ($request->get('language')) {
                $query->whereHas('languages', function ($query) use ($request) {
                    $query->where('code', $request->language);
                });
            }

            if ($request->get('groupType')) {
                $query->whereHas('groupType', function ($query) use ($request) {
                    $query->where('identifier', $request->groupType);
                });
            }

            if ($request->get('virtual')) {
                $query->where('virtual', ($request->virtual == 'yes'));
            }

            if ($request->get('tag')) {
                $query->withAnyTags([$request->tag]);
            }

            if ($request->get('search')) {
                $query->where(function ($query) use ($request) {
                    $tags = Tag::containing($request->search)->get();
                    $query->withAnyTags($tags);

                    $query->orWhere('title', 'LIKE', '%' . $request->search . '%');
                    $query->orWhere('description', 'LIKE', '%' . $request->search . '%');
                    $query->orWhere('location', 'LIKE', '%' . $request->search . '%');
                });
            }

            $query->orderBy('created_at', 'desc');

            return $query->get();
        });

        return $peergroups;
    }

    /**
     * @source https://gist.github.com/vluzrmos/3ce756322702331fdf2bf414fea27bcb
     */
    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function getResetFilterLink($filter_key)
    {
        $urlParams = array_filter(request()->query());

        unset($urlParams['page']);

        if (key_exists($filter_key, $urlParams)) {
            unset($urlParams[$filter_key]);
            return route('matcher.index', $urlParams);
        } else {
            return null;
        }
    }

    public function getDefaultFilters()
    {
        return $this->filters;
    }

    public function isAnyFilterSet()
    {
        $urlParams = array_filter(request()->query());

        if (count(array_intersect_key($this->filters, $urlParams)) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function notifyAllOwners($pg, $notification, $ignore_user = null)
    {
        $owners = [];

        if ($ignore_user == null || $ignore_user->id != $pg->user_id) {
            $owners[] = $pg->user;
        }

        foreach ($pg->memberships as $membership) {
            if (
                $membership->member_role_id == Membership::ROLE_CO_OWNER &&
                $membership->user_id != $pg->user_id &&
                ($ignore_user == null || $ignore_user->id != $membership->user_id)
            ) {
                $owners[] = $membership->user;
            }
        }

        foreach ($owners as $owner) {
            $owner->notify($notification);
        }
    }

    public function applyMemberRoles(Request $request, Peergroup $pg)
    {
        $roles = $request->get('roles', []);
        $usernames = $request->get('usernames', []);

        if (count($roles) != count($usernames) || count($roles) < 1) {
            abort(404);
        }

        for ($i = 0; $i < count($usernames); $i++) {
            $user = User::whereUsername($usernames[$i])->firstOrFail();

            if (!key_exists($roles[$i], Membership::memberRoles())) {
                continue;
            }

            // Don't apply any changes to the owner's membership
            if ($pg->isOwner($user)) {
                continue;
            }

            $membership = Membership::where(['peergroup_id' => $pg->id, 'user_id' => $user->id, 'approved' => true])->firstOrFail();

            $membership->member_role_id = $roles[$i];
            $membership->save();
        }
    }

    public function downloadAppointment(Peergroup $pg, Appointment $appointment)
    {
        $eventUid = new \Eluceo\iCal\Domain\ValueObject\UniqueIdentifier($appointment->identifier);

        $event = new \Eluceo\iCal\Domain\Entity\Event($eventUid);

        $event->setSummary($appointment->subject);

        $description = [];

        if ($appointment->details) {
            $description[] = $appointment->details;
        }

        $description[] = __('matcher::peergroup.appointment_ical_description', ['title' => $pg->title, 'link' => route('matcher.appointments.show', ['pg' => $pg->groupname, 'appointment' => $appointment->identifier])]);

        $event->setDescription(implode("\n\n", $description));

        if ($appointment->location) {
            $location = new \Eluceo\iCal\Domain\ValueObject\Location($appointment->location);
            $event->setLocation($location);
        }

        $begin = new \Eluceo\iCal\Domain\ValueObject\DateTime(EasyDate::fromUTC($appointment->date), true);
        $end = new \Eluceo\iCal\Domain\ValueObject\DateTime(EasyDate::fromUTC($appointment->end_date), true);

        $timeSpan = new \Eluceo\iCal\Domain\ValueObject\TimeSpan($begin, $end);

        $event->setOccurrence($timeSpan);

        $event->addAlarm(new \Eluceo\iCal\Domain\ValueObject\Alarm(
            new \Eluceo\iCal\Domain\ValueObject\Alarm\DisplayAction(__('matcher::peergroup.appointment_ical_reminder')),
            (new \Eluceo\iCal\Domain\ValueObject\Alarm\RelativeTrigger(\DateInterval::createFromDateString('-60 minutes')))
        ));

        $calendar = new \Eluceo\iCal\Domain\Entity\Calendar([$event]);

        if (auth()->user()->timezone) {
            $phpTz = new DateTimeZone('Europe/Berlin');
            $tz = \Eluceo\iCal\Domain\Entity\TimeZone::createFromPhpDateTimeZone($phpTz, EasyDate::fromUTC($appointment->date), EasyDate::fromUTC($appointment->end_date));
            $calendar->addTimeZone($tz);
        }

        $iCalendarComponent = (new \Eluceo\iCal\Presentation\Factory\CalendarFactory())->createCalendar($calendar);

        return Response::make($iCalendarComponent, 200, array(
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => sprintf('attachment; filename="%s.ics"', $appointment->identifier),
        ));
    }

    public function tagsForForm($fieldName, $savedTags)
    {
        if (old('_token')) {
            $oldTags = new Collection(old($fieldName, []));
            return $oldTags->map(fn ($tag) => ['value' => $tag, 'id' => $tag]);
        } else {
            return $savedTags->map(fn ($tag) => ['value' => $tag->name, 'id' => $tag->name]);
        }
    }

    public function createInvitation(Request $request, Peergroup $pg)
    {
        $input = $request->all();

        $sender = auth()->user();

        Validator::make($input, Invitation::rules()['create'])->validate();

        $users = User::whereIn('username', $input['search_users'])->get();

        $can_approve = $sender->can('approve', [Membership::class, $pg]);
        
        $users->each(function ($user) use ($pg, $sender, $request, $can_approve) {
            // Skip peergroup owner and member, they don't need an invitation
            if ($pg->isMember($user) || $pg->isOwner($user)) {
                return;
            }

            if ($can_approve) {
                if ($pg->isMember($user, true) && !$pg->isFull()) {
                    $this->approveMember($pg, $user);
                    return;
                }
            }

            $invitation = Invitation::wherePeergroupId($pg->id)->whereReceiverUserId($user->id)->first();

            if ($invitation) {
                return;
            }

            $invitation = Invitation::create([
                'peergroup_id' => $pg->id,
                'receiver_user_id' => $user->id,
                'sender_user_id' => $sender->id,
                'comment' => $request->comment,
            ]);

            $this->afterInvitationCreated($pg, $user, $sender, $invitation);
        });
    }

    public function deleteInvitations(Peergroup $pg, User $user)
    {
        Invitation::wherePeergroupId($pg->id)->whereReceiverUserId($user->id)->delete();
    }
}
