<?php

namespace Matcher\Http\Controllers;

use App\Models\User;
use Matcher\Models\Peergroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Matcher\Exceptions\MembershipException;
use Matcher\Facades\Matcher;
use Matcher\Models\Membership;

class MembershipController extends Controller
{
    public function create(Request $request, Peergroup $pg)
    {
        Gate::authorize('create', [Membership::class, $pg]);

        try {
            Matcher::canUserJoinGroup($pg, auth()->user());
        } catch (MembershipException $e) {
            return redirect($pg->getUrl())->with('error', $e->getMessage());
        }

        return view('matcher::membership.create', compact('pg'));
    }

    public function delete(Request $request, Peergroup $pg)
    {
        $membership = Membership::where(['peergroup_id' => $pg->id, 'user_id' => $request->user()->id, 'approved' => true])->firstOrFail();

        Gate::authorize('delete', [$membership, $pg]);

        return view('matcher::membership.delete', compact('pg'));
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

    public function destroy(Request $request, Peergroup $pg)
    {
        $membership = Membership::where(['peergroup_id' => $pg->id, 'user_id' => $request->user()->id, 'approved' => true])->firstOrFail();

        Gate::authorize('delete', [$membership, $pg]);

        Matcher::removeMemberFromGroup($pg, $request->user());

        return redirect($pg->getUrl())->with('success', __('matcher::peergroup.peergroup_left_successfully'));
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

        Gate::authorize('decline', [$membership, $pg]);

        $membership->delete();
        
        return redirect($pg->getUrl())->with('success', __('matcher::peergroup.member_declined_successfully'));
    }    
}