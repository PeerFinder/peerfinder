<?php

namespace Talk;

class Talk
{
    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function listOfUsers($conversation)
    {
        $users = $conversation->users->all();

        if (count($users) > 1) {
            $users = array_filter($users, fn($user) => $this->user->id != $user->id);
        }

        return $users;
    }

    public function usersAsString($users)
    {
        return implode(", ", array_map(fn($user) => $user->name, $users));
    }
}