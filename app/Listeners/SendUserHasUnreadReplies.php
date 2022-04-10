<?php

namespace App\Listeners;

use App\Enums\NotificationSettingStatus;
use App\Enums\NotificationSettingType;
use App\Helpers\Facades\NotificationCenter;
use App\Notifications\UserHasUnreadReplies;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendUserHasUnreadReplies
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $notificationStatus = NotificationCenter::getNotificationSetting($event->receipt->user, NotificationSettingType::UnreadMessages);

        if ($notificationStatus != NotificationSettingStatus::Disabled) {
            $event->receipt->user->notify(new UserHasUnreadReplies($event->receipt));
        }
    }
}
