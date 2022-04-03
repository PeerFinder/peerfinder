<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class NotificationSettingsController extends Controller
{
    public function edit(Request $request)
    {
        return view('frontend.account.notification_settings.edit', [
            'user' => $request->user(),
            
        ]);
    }

    public function update(Request $request)
    {
/*         $input = $request->input();

        Validator::make($input, [
            'locale' => ['required', Rule::in(config('app.available_locales'))],
            'timezone' => ['required', 'timezone'],
        ])->validate();

        $request->user()->update($input);

        return redirect()->back()->with('success', __('account/settings.settings_changed_successfully')); */
    }
}
