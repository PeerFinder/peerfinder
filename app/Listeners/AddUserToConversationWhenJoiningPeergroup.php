<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddUserToConversationWhenJoiningPeergroup
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
        if ($event->pg->conversations()->count() < 1) {
            CreateConversationForPeergroup::createConversationForPeergroup($event->pg);
        }

        $event->pg->conversations()->each(function ($conversation) use ($event) {
            $conversation->addUser($event->user);
        });
    }
}
