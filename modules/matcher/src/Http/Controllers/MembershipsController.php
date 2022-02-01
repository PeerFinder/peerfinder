<?php

namespace Matcher\Http\Controllers;

use App\Models\User;
use Matcher\Models\Peergroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Matcher\Exceptions\MembershipException;
use Matcher\Facades\Matcher;
use Matcher\Models\Membership;

class MembershipsController extends Controller
{
    private function getMembershipOrFail(User $user, Peergroup $pg, $approved = true)
    {
        return Membership::where(['peergroup_id' => $pg->id, 'user_id' => $user->id, 'approved' => $approved])->firstOrFail();
    }

    private function getUserFromRequest(Request $request, $username)
    {
        if ($username) {
            $user = User::whereUsername($username)->firstOrFail();
        } else {
            $user = $request->user();
        }

        return $user;
    }

    public function index(Request $request, Peergroup $pg)
    {
        Gate::authorize('manage-members', $pg);

        return view('matcher::memberships.index', compact('pg'));
    }

    public function create(Request $request, Peergroup $pg)
    {
        Gate::authorize('create', [Membership::class, $pg]);

        try {
            Matcher::canUserJoinGroup($pg, auth()->user());
        } catch (MembershipException $e) {
            return redirect($pg->getUrl())->with('error', $e->getMessage());
        }

        return view('matcher::memberships.create', compact('pg'));
    }

    public function edit(Request $request, Peergroup $pg)
    {
        $membership = $this->getMembershipOrFail($request->user(), $pg);

        Gate::authorize('edit', [$membership, $pg]);

        return view('matcher::memberships.edit', compact('pg', 'membership'));
    }

    public function update(Request $request, Peergroup $pg)
    {
        $membership = $this->getMembershipOrFail($request->user(), $pg);

        Gate::authorize('edit', [$membership, $pg]);

        $input = $request->all();
        
        Validator::make($input, Membership::rules()['update'])->validate();

        $membership->update($input);
        
        return redirect($pg->getUrl())->with('success', __('matcher::peergroup.membership_updated_successfully'));
    }

    public function delete(Request $request, Peergroup $pg, $username = null)
    {
        $user = $this->getUserFromRequest($request, $username);

        $membership = $this->getMembershipOrFail($user, $pg);

        Gate::authorize('delete', [$membership, $pg]);

        $is_self = $user->id == $request->user()->id;

        return view('matcher::memberships.delete', compact('pg', 'user', 'is_self'));
    }

    public function store(Request $request, Peergroup $pg)
    {
        Gate::authorize('create', [Membership::class, $pg]);

        $input = $request->all();
        
        try {
            $membership = Matcher::addMemberToGroup($pg, auth()->user(), $input);
        } catch (MembershipException $e) {
            return redirect($pg->getUrl())->with('error', $e->getMessage());
        }

        if ($membership->approved) {
            return redirect($pg->getUrl())->with('success', __('matcher::peergroup.peergroup_joined_successfully'));
        } else {
            return redirect($pg->getUrl())->with('success', __('matcher::peergroup.peergroup_waiting_for_approval'));
        }
    }

    public function destroy(Request $request, Peergroup $pg, $username = null)
    {
        $user = $this->getUserFromRequest($request, $username);

        $membership = $this->getMembershipOrFail($user, $pg);

        Gate::authorize('delete', [$membership, $pg]);

        Matcher::removeMemberFromGroup($pg, $user);

        if ($user->id == $request->user()->id) {
            $message = __('matcher::peergroup.peergroup_left_successfully');
        } else {
            $message = __('matcher::peergroup.member_removed_successfully', ['name' => $user->name]);
        }

        return redirect($pg->getUrl())->with('success', $message);
    }

    public function approve(Request $request, Peergroup $pg, $username)
    {
        $user = User::where(['username' => $username])->firstOrFail();

        $membership = Membership::where(['peergroup_id' => $pg->id, 'user_id' => $user->id, 'approved' => false])->firstOrFail();

        Gate::authorize('approve', [$membership, $pg]);

        try {
            Matcher::approveMember($pg, $user);
        } catch (MembershipException $e) {
            return redirect($pg->getUrl())->with('error', $e->getMessage());
        }

        return redirect($pg->getUrl())->with('success', __('matcher::peergroup.member_approved_successfully'));
    }

    public function decline(Request $request, Peergroup $pg, $username)
    {
        $user = User::where(['username' => $username])->firstOrFail();

        $membership = Membership::where(['peergroup_id' => $pg->id, 'user_id' => $user->id, 'approved' => false])->firstOrFail();

        Gate::authorize('approve', [$membership, $pg]);

        $membership->delete();
        
        return redirect($pg->getUrl())->with('success', __('matcher::peergroup.member_declined_successfully'));
    }

    public function manage(Request $request, Peergroup $pg)
    {
        Gate::authorize('manage-members', $pg);

        Matcher::applyMemberRoles($request, $pg);

        return redirect($pg->getUrl())->with('success', __('matcher::peergroup.members_managed_successfully'));
    }
}