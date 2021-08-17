<?php

namespace Talk\Traits;

use Talk\Models\Conversation;

trait UserConversations
{
    public function participated_conversations()
    {
        return $this->belongsToMany(Conversation::class)->withTimestamps();
    }

    public function owned_conversations()
    {
        return $this->morphMany(Conversation::class, 'conversationable');
    }
}