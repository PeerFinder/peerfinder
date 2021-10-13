<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function edit(Request $request)
    {
        $locale_names = [
            "de" => "Deutsch",
            "en" => "English",
            "fr" => "Français",
            "es" => "Español",
            "it" => "Italiano",
        ];

        $locales = array_filter($locale_names, fn ($key) => in_array($key, config('app.available_locales')), ARRAY_FILTER_USE_KEY);

        $timezones = timezone_identifiers_list(\DateTimeZone::ALL);

        return view('frontend.account.settings.edit', [
            'user' => $request->user(),
            'locales' => $locales,
            'timezones' => array_combine($timezones, $timezones),
        ]);
    }

    public function update(Request $request)
    {
        $input = $request->input();

        Validator::make($input, [
            'locale' => ['required', Rule::in(config('app.available_locales'))],
            'timezone' => ['required', 'timezone'],
        ])->validate();

        $request->user()->update($input);

        return redirect()->back()->with('success', __('account/settings.settings_changed_successfully'));
    }
}
