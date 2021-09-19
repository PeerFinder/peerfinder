<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Talk\Facades\Talk;
use Talk\Models\Conversation;

class DeleteConversationsForPeergroup
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
        $event->pg->conversations()->each(function (Conversation $conversation) {
            $conversation->delete();
        });
    }
}
