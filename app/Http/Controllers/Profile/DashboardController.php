<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\Facades\Urler;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Matcher\Models\Peergroup;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $own_peergroups = $user->peergroups()->with(Peergroup::defaultRelationships())->get();

        $memberships = $user->memberships()->where('approved', true)->pluck('peergroup_id');

        $member_peergroups = Peergroup::whereIn('id', $memberships->all())->with(Peergroup::defaultRelationships())->get();

        return view('frontend.profile.dashboard.index', compact('own_peergroups', 'member_peergroups'));
    }
}
