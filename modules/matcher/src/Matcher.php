<?php

namespace Matcher;

use App\Models\User;
use App\Rules\ConfirmCheckbox;
use Illuminate\Http\Request;
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

class Matcher
{
    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function cleanupForUser(User $user)
    {
        $user->peergroups()->each(function ($pg) {
            $pg->delete();
        });

        $user->memberships()->each(function ($membership) {
            $membership->delete();
        });
    }

    public function storePeergroupData($pg, Request $request, $mode = 'update')
    {
        $input = $request->all();

        Validator::make($input, Peergroup::rules()[$pg ? 'update' : 'create'])->validate();

        if ($pg) {
            $pg->update($input);
        } else {
            $pg = new Peergroup($input);
            $pg->user()->associate($request->user());
            $pg->open = true;
            $pg->save();
        }

        $languages = Language::whereIn('code', array_values($request->languages))->get();

        $pg->languages()->sync($languages);

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

        # User without invitation cannot join private groups. Owners can.
        if (!$pg->allowedToJoin($user)) {
            throw new MembershipException(__('matcher::peergroup.exception_cannot_join_private_group'));
        }

        # If the group is full, nobody can join
        if ($pg->isFull()) {
            throw new MembershipException(__('matcher::peergroup.exception_limit_is_reached'));
        }

        # If the group is marked as completed/closed nobody can join
        if (!$pg->isOpen()) {
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
}
