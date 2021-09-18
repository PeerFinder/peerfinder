<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Talk\Facades\Talk;
use Talk\Models\Conversation;

class CreateConversationForPeergroup
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    static function createConversationForPeergroup($pg)
    {
        $conversation = new Conversation();
        $conversation->setOwner($pg);
        $conversation->save();
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        self::createConversationForPeergroup($event->pg);
    }
}
