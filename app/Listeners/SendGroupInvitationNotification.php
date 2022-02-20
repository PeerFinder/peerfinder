<?php

namespace App\Listeners;

use App\Notifications\GroupInvitationReceived;
use App\Notifications\NewMemberInGroup;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Matcher\Facades\Matcher;

class SendGroupInvitationNotification
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
        $event->receiver->notify(new GroupInvitationReceived($event->pg, $event->receiver, $event->sender, $event->invitation));
    }
}