<?php

namespace App\Listeners;

use App\Notifications\NewMemberInGroup;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
        # Don't send the notification if the user owner is joining the group
        if ($event->pg->user->id != $event->user->id) {
            $event->pg->user->notify(new NewMemberInGroup($event->pg, $event->user));
        }
    }
}
