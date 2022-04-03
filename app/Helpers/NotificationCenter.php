<?php

namespace App\Helpers;

use App\Enums\NotificationSettingStatus;
use App\Enums\NotificationSettingType;
use App\Models\NotificationSetting;
use App\Models\User;
use Illuminate\Support\Str;

class NotificationCenter
{
    public function notificationEnabled(User $user, NotificationSettingType $type): bool
    {
        $existingNotification = NotificationSetting::whereUserId($user->id)->whereNotificationType($type->value)->first();

        $defaultStatus = NotificationSetting::defaultStatus();

        // If the notification settings is not set yet, check if the setting is disabled by default.
        if ($existingNotification == null) {
            // If the settings is disabled by default, return false
            if ($defaultStatus[$type->value] == NotificationSettingStatus::Disabled) {
                return false;
            } else {
                // If the default setting is not disabled, create a new notification setting
                $notification = new NotificationSetting();

                $notification->user_id = $user->id;
                $notification->notification_type = $type;
                $notification->notification_status = $defaultStatus[$type->value];
        
                $notification->save();

                return true;
            }
        } else {
            return $existingNotification->notification_status != NotificationSettingStatus::Disabled;
        }
    }

    public function setNotificationSetting(User $user, NotificationSettingType $type, NotificationSettingStatus $status)
    {
        $notification = NotificationSetting::whereUserId($user->id)->whereNotificationType($type->value)->first();

        if ($notification == null) {
            $notification = new NotificationSetting();
            $notification->user_id = $user->id;
            $notification->notification_type = $type;
        }

        $notification->notification_status = $status;

        $notification->save();
    }
}