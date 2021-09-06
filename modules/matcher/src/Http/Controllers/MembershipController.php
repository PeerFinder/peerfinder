<?php

namespace Matcher\Http\Controllers;

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
        return view('matcher::membership.create', compact('pg'));
    }

    public function store(Request $request, Peergroup $pg)
    {
        Gate::authorize('create', [Membership::class, $pg]);

        try {
            Matcher::addMemberToGroup($pg, auth()->user());
        } catch (MembershipException $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect($pg->getUrl())->with('success', __('matcher::peergroup.peergroup_joined_successfully'));
    }
}