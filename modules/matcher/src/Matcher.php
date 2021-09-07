<?php

namespace Matcher;

use App\Models\User;
use App\Rules\ConfirmCheckbox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Matcher\Exceptions\MembershipException;
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

    public function addMemberToGroup(Peergroup $pg, User $user)
    {
        $this->canUserJoinGroup($pg, $user);

        $membership = new Membership();
        $membership->peergroup_id = $pg->id;
        $membership->user_id = $user->id;
        
        if ($pg->needsApproval($user)) {
            $membership->approved = false;
        } else {
            $membership->approved = true;
        }

        $membership->save();

        $pg->updateStates();

        return $membership;
    }

    public function removeMemberFromGroup(Peergroup $pg, User $user)
    {
        Membership::where(['peergroup_id' => $pg->id, 'user_id' => $user->id])->delete();
    }
}
