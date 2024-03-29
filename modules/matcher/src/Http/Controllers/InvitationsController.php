<?php

namespace Matcher\Http\Controllers;

use App\Models\User;
use Matcher\Models\Peergroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Matcher\Exceptions\MembershipException;
use Matcher\Facades\Matcher;
use Matcher\Models\Invitation;

class InvitationsController extends Controller
{
    public function create(Request $request, Peergroup $pg)
    {
        Gate::authorize('create', [Invitation::class, $pg]);

        return view('matcher::invitations.create', compact('pg'));
    }

    public function store(Request $request, Peergroup $pg)
    {
        Gate::authorize('create', [Invitation::class, $pg]);

        Matcher::createInvitation($request, $pg);

        return redirect($pg->getUrl())->with('success', __('matcher::peergroup.invitation_created_successfully'));
    }

    public function destroy(Request $request, Peergroup $pg)
    {
        $invitation = Invitation::wherePeergroupId($pg->id)->whereReceiverUserId(auth()->id())->firstOrFail();

        Matcher::deleteInvitations($pg, auth()->user());

        return back()->with('success', __('matcher::peergroup.invitation_deleted_successfully'));
    }
}