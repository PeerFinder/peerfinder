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
            'user' => $request->user()
        ]);
    }

    public function update(Request $request)
    {
        $input = $request->all();

        if (key_exists('homepage', $input) && $input['homepage']) {
            $input['homepage'] = Urler::fullUrl($input['homepage']);
        }

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'slogan' => ['nullable', 'string', 'max:255'],
            'homepage' => ['nullable', 'max:255', new UrlerValidUrl()]
        ])->validate();

        $request->user()->update($input);

        return redirect()->back()->with('success', __('account/profile.profile_changed_successfully'));
    }
}
