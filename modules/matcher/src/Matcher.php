<?php

namespace Matcher;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Matcher\Exceptions\MembershipException;
use Matcher\Models\Peergroup;
use Matcher\Models\Language;
use Matcher\Models\Membership;

class Matcher
{
    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function cleanupForUser(User $user)
    {
        $user->peergroups()->each(function ($peergroup) {
            $peergroup->delete();
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

        Validator::make($input, ['confirm_delete' => ['required', 'boolean']])->after(function ($validator) use($input) {
            if ($input['confirm_delete'] !== '1') {
                $validator->errors()->add('confirm_delete', __('matcher::peergroup.delete_group_confirm_validation_error'));
            }
        })->validate();

        $pg->delete();
    }

    public function addMemberToGroup(Peergroup $pg, User $user)
    {
        # User is already a member? Nothing to do
        if ($pg->isMember($user)) {
            throw new MembershipException(__('matcher::peergroup.exception_user_already_member', ['user' => $user->name]));
        }

        $members_count = $pg->members()->count();

        if ($members_count < $pg->limit) {
            $membership = new Membership();
            $membership->peergroup_id = $pg->id;
            $membership->user_id = $user->id;
            
            if ($pg->needsApproval($user)) {
                $membership->approved = false;
            } else {
                $membership->approved = true;
            }

            $membership->save();
        } else {
            throw new MembershipException(__('matcher::peergroup.exception_limit_is_reached'));
        }

        $pg->updateStates();

        return $membership;
    }
}
