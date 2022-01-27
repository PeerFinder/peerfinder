<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\Facades\Urler;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Matcher\Models\Peergroup;

class UserController extends Controller
{
    public function index()
    {
        return redirect(auth()->user()->profileUrl());
    }

    public function show(User $user)
    {
        $available_profiles = [];

        foreach (array_keys(Urler::getSocialPlatforms()) as $platform) {
            $profile_url = $user->getAttribute($platform . '_profile');
            
            if ($profile_url) {
                $available_profiles[$platform] = $profile_url;
            }
        }

        $memberships = $user->memberships()->where('approved', true)->pluck('peergroup_id');

        $member_peergroups = Peergroup::whereIn('id', $memberships->all())->where('private', false)->with(Peergroup::defaultRelationships())->get();

        return view('frontend.profile.user.show', [
            'user' => $user,
            'platforms' => $available_profiles,
            'member_peergroups' => $member_peergroups,
        ]);
    }

    public function search(Request $request)
    {
        $jsonArray = [
            'users' => [],
        ];

        if ($request->has('name')) {
            $users = User::where('email_verified_at', '<>', null)->where('name', 'LIKE', '%' . $request->name .'%')->select('username', 'name')->get();
            $jsonArray['users'] = $users->toArray();
        }

        return response()->json($jsonArray);
    }
}
