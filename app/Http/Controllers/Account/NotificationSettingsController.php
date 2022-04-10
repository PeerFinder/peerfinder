<?php

namespace App\Http\Controllers\Account;

use App\Enums\NotificationSettingStatus;
use App\Enums\NotificationSettingType;
use App\Helpers\Facades\NotificationCenter;
use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class NotificationSettingsController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();

        return view('frontend.account.notification_settings.edit', [
            'user' => $user,
            'statusOptions' => [
                NotificationSettingStatus::Disabled->value => __('account/notification_settings.notification_setting_status_disabled'),
                NotificationSettingStatus::Mail->value => __('account/notification_settings.notification_setting_status_mail'),
            ],
            'statusValues' => [
                NotificationSettingType::UnreadMessages->name => NotificationCenter::getNotificationSetting($user, NotificationSettingType::UnreadMessages),
                NotificationSettingType::NewGroupsNewsletter->name => NotificationCenter::getNotificationSetting($user, NotificationSettingType::NewGroupsNewsletter),
            ]
        ]);
    }

    public function update(Request $request)
    {
        $input = $request->input();
        $user = $request->user();

        Validator::make($input, [
            NotificationSettingType::UnreadMessages->name => ['required', 'numeric', new Enum(NotificationSettingStatus::class)],
            NotificationSettingType::NewGroupsNewsletter->name => ['required', 'numeric' , new Enum(NotificationSettingStatus::class)],
        ])->validate();

        NotificationCenter::setNotificationSetting($user, NotificationSettingType::UnreadMessages, NotificationSettingStatus::from($input[NotificationSettingType::UnreadMessages->name]));
        NotificationCenter::setNotificationSetting($user, NotificationSettingType::NewGroupsNewsletter, NotificationSettingStatus::from($input[NotificationSettingType::NewGroupsNewsletter->name]));

        return redirect()->back()->with('success', __('account/notification_settings.notification_settings_changed_successfully'));
    }

    public function unsubscribe($identifier)
    {
        $notification = NotificationSetting::whereIdentifier($identifier)->firstOrFail();

        $notification->notification_status = NotificationSettingStatus::Disabled;
        $notification->save();

        return view('frontend.account.notification_settings.unsubscribe');
    }
}
