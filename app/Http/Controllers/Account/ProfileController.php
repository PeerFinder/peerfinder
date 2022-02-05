<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Facades\Urler;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\UrlerValidUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('frontend.account.profile.edit', [
            'user' => $request->user(),
            'platforms' => array_keys(Urler::getSocialPlatforms()),
        ]);
    }

    public function update(Request $request)
    {
        $input = $request->all();

        if (key_exists('homepage', $input) && $input['homepage']) {
            $input['homepage'] = Urler::fullUrl($input['homepage']);
        }

        foreach(array_keys(Urler::getSocialPlatforms()) as $platform) {
            $field_name = $platform . '_profile';

            if (key_exists($field_name, $input) && $input[$field_name]) {
                $input[$field_name] = Urler::sanitizeSocialMediaProfileUrl($platform, $input[$field_name]);
            }
        }

        Validator::make($input, User::rules()['update'])->validate();

        $request->user()->update($input);

        return redirect()->back()->with('success', __('account/profile.profile_changed_successfully'));
    }
}
