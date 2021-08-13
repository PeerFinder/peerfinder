<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Facades\Urler;
use App\Http\Controllers\Controller;
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

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'slogan' => ['nullable', 'string', 'max:255'],
            'homepage' => ['nullable', 'max:255', new UrlerValidUrl()],
            'company' => ['nullable', 'string', 'max:255'],
            'facebook_profile' => ['nullable', new UrlerValidUrl(), 'max:255'],
            'linkedin_profile' => ['nullable', new UrlerValidUrl(), 'max:255'],
            'twitter_profile' => ['nullable', new UrlerValidUrl(), 'max:255'],
            'xing_profile' => ['nullable', new UrlerValidUrl(), 'max:255'],
        ])->validate();

        $request->user()->update($input);

        return redirect()->back()->with('success', __('account/profile.profile_changed_successfully'));
    }
}
