<?php

namespace Talk\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Talk\Models\Conversation;

class CreateConversation
{
    public function forUser(User $user, $input)
    {
        Validator::make($input, Conversation::getValidationRules()['create'])->validate();
        $conversation = $user->owned_conversations()->create($input);
        $conversation->users()->attach($user);
        return $conversation;
    }
}