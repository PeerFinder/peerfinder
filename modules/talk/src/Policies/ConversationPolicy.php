<?php

namespace Talk\Policies;

use Talk\Models\Conversation;

class ConversationPolicy
{
    public function view($user, Conversation $conversation)
    {
        return $conversation->isParticipant($user);
    }

    public function edit($user, Conversation $conversation)
    {
        return $conversation->isParticipant($user);
    }

    public function join($user, Conversation $conversation)
    {
        return $conversation->isOwnerGroupRequest();
    }

    public function leave($user, Conversation $conversation)
    {
        return $conversation->isOwnerGroupRequest();
    }
}
