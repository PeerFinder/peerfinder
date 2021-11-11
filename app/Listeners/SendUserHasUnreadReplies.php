<?php

namespace App\Listeners;

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
        $event->receipt->user->notify(new UserHasUnreadReplies($event->receipt));
    }
}
