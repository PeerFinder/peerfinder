<?php

namespace App\Models;

use App\Enums\NotificationSettingStatus;
use App\Enums\NotificationSettingType;
use App\Helpers\Facades\Urler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;

    protected $casts = [
        'notification_type' => NotificationSettingType::class,
        'notification_status' => NotificationSettingStatus::class,
    ];

    public static function defaultStatus()
    {
        $status = [];
        $status[NotificationSettingType::UnreadMessages->value] = NotificationSettingStatus::Mail;
        $status[NotificationSettingType::NewGroupsNewsletter->value] = NotificationSettingStatus::Disabled;
        return $status;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($notificationSetting) {
            Urler::createUniqueSlug($notificationSetting, 'identifier');
        });
    }
}
