<?php

namespace Talk\Traits;

use Talk\Models\Conversation;
use Talk\Models\Receipt;
use Talk\Models\Reply;

trait PeergroupConversations
{
    public function conversations()
    {
        return $this->morphMany(Conversation::class, 'conversationable');
    }
}