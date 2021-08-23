<?php

namespace Talk\Traits;

use Talk\Models\Conversation;
use Talk\Models\Receipt;
use Talk\Models\Reply;

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

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }
}