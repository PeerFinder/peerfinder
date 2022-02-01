<?php

namespace Talk\Policies;

use Talk\Models\Conversation;
use Talk\Models\Reply;

class ReplyPolicy
{
    public function view($user, Reply $reply, Conversation $conversation)
    {
        return $conversation->isParticipant($user) && $reply->conversation_id == $conversation->id;
    }

    public function edit($user, Reply $reply, Conversation $conversation)
    {
        return $conversation->isParticipant($user) && $reply->conversation_id == $conversation->id && $reply->user_id == $user->id;
    }
}
