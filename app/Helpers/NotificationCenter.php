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
        return $this->getNotificationSetting($user, $type) != NotificationSettingStatus::Disabled;
    }

    public function getNotificationSetting(User $user, NotificationSettingType $type): NotificationSettingStatus
    {
        $notification = NotificationSetting::whereUserId($user->id)->whereNotificationType($type->value)->first();

        if ($notification != null) {
            return $notification->notification_status;
        } else {
            $defaultStatus = NotificationSetting::defaultStatus();

            $notification = new NotificationSetting();

            $notification->user_id = $user->id;
            $notification->notification_type = $type;
            $notification->notification_status = $defaultStatus[$type->value];
    
            $notification->save();

            return $notification->notification_status;
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

    public function getUnsubscribeLink(User $user, NotificationSettingType $type)
    {
        $notification = NotificationSetting::whereUserId($user->id)->whereNotificationType($type->value)->first();

        if ($notification != null) {
            return route('notifications.unsubscribe', ['identifier' => $notification->identifier]);
        } else {
            return null;
        }
    }
}