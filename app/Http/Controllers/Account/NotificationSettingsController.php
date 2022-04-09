<?php

namespace App\Http\Controllers\Account;

use App\Enums\NotificationSettingStatus;
use App\Enums\NotificationSettingType;
use App\Helpers\Facades\NotificationCenter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

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
        $input = $request->input();

        Validator::make($input, [
            NotificationSettingType::UnreadMessages->name => ['required', 'numeric', new Enum(NotificationSettingStatus::class)],
            NotificationSettingType::NewGroupsNewsletter->name => ['required', 'numeric' , new Enum(NotificationSettingStatus::class)],
        ])->validate();

        NotificationCenter::setNotificationSetting($request->user(), NotificationSettingType::UnreadMessages, NotificationSettingStatus::from($input[NotificationSettingType::UnreadMessages->name]));
        NotificationCenter::setNotificationSetting($request->user(), NotificationSettingType::NewGroupsNewsletter, NotificationSettingStatus::from($input[NotificationSettingType::NewGroupsNewsletter->name]));

        return redirect()->back()->with('success', __('account/notification_settings.notification_settings_changed_successfully'));
    }
}
