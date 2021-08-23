<?php

namespace Talk\Policies;

use Talk\Models\Conversation;
use Talk\Traits\AdminPolicies;

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
}
