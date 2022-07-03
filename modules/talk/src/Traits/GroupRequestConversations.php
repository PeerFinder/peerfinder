<?php

namespace Talk\Traits;

use Talk\Models\Conversation;

trait GroupRequestConversations
{
    public function conversations()
    {
        return $this->morphMany(Conversation::class, 'conversationable');
    }
}