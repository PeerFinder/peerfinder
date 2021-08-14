<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\Facades\Urler;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return redirect(route('profile.user.show', ['user' => auth()->user()->username]));
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

        return view('frontend.profile.user.show', [
            'user' => $user,
            'platforms' => $available_profiles,
        ]);
    }
}
