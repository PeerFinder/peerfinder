<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\Facades\Urler;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Matcher\Models\Peergroup;
use Matcher\Models\Tag;

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
        // JSON search is used for Ajax
        if($request->wantsJson()) {
            $jsonArray = [
                'users' => [],
            ];
    
            if ($request->has('name') && strlen($request->name) > 1) {
                $users = User::where('email_verified_at', '<>', null)
                                ->where('name', 'LIKE', '%' . $request->name .'%')
                                ->select('username', 'name')
                                ->limit(config('user.search.limit'))
                                ->get();
                
                $jsonArray['users'] = $users->toArray();
            }
    
            return response()->json($jsonArray);
        } else {
            $users = null;

            if ($request->has('search') && strlen($request->search) > 1) {
                $tags = Tag::containing($request->search)->get();

                $query = User::where('email_verified_at', '<>', null)
                                ->withAnyTags($tags)
                                ->orWhere('name', 'LIKE', '%' . $request->search .'%')
                                ->orWhere('about', 'LIKE', '%' . $request->search .'%')
                                ->orWhere('slogan', 'LIKE', '%' . $request->search .'%');
                                
                $users = $query->paginate()->appends(['search' => $request->search]);
            }

            return view('frontend.profile.user.search', [
                'users' => $users,
            ]);
        }
    }
}
