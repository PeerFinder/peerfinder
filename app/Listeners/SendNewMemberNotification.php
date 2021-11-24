<?php

namespace App\Listeners;

use App\Notifications\NewMemberInGroup;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Matcher\Facades\Matcher;

class SendNewMemberNotification
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
        # Don't send the notification if the owner is joining the group
        Matcher::notifyAllOwners($event->pg, new NewMemberInGroup($event->pg, $event->user), $event->user);
    }
}
