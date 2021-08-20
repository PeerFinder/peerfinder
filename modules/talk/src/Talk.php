<?php

namespace Talk;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Talk\Models\Conversation;

class Talk
{
    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function filterUsers($users)
    {
        if (count($users) > 1) {
            $users = array_filter($users, fn($user) => $this->user->id != $user->id);
        }

        return $users;
    }

    public function filterUsersForConversation($conversation)
    {
        return $this->filterUsers($conversation->users()->get()->all());
    }

    public function usersAsString($users)
    {
        return implode(", ", array_map(fn($user) => $user->name, $users));
    }

    public function createConversationForUser(User $user, $input)
    {
        Validator::make($input, Conversation::getValidationRules()['create'])->validate();
        $conversation = $user->owned_conversations()->create($input);
        $conversation->addUser($user);
        return $conversation;
    }

    public function deleteConversationForUser(User $user)
    {
        $user->participated_conversations()->detach();

        $user->owned_conversations()->each(function ($conversation) {
            $conversation->delete();
        });
    }
}
