<?php

namespace Talk\Actions;

use App\Models\User;

class DeleteConversations
{
    public function forUser(User $user)
    {
        $user->participated_conversations()->detach();

        $user->owned_conversations()->each(function ($conversation) {
            $conversation->delete();
        });
    }
}