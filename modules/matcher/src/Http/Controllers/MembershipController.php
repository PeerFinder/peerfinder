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
        
        try {
            $membership = Matcher::addMemberToGroup($pg, auth()->user());
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
}