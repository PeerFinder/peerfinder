<?php

namespace App\Listeners;

use App\Notifications\UserRequestsToJoinGroup;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Matcher\Facades\Matcher;

class SendUserRequestsToJoinGroupNotification
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
        Matcher::notifyAllOwners($event->pg, new UserRequestsToJoinGroup($event->pg, $event->user));
    }
}
