<?php

namespace Talk;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Talk\Models\Conversation;
use Talk\Models\Receipt;
use Talk\Models\Reply;

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

    public function createReply(Conversation $conversation, User $user, $input, $replyTo = null)
    {
        Validator::make($input, Reply::getValidationRules()['create'])->validate();

        $reply = new Reply();
        $reply->conversation()->associate($conversation);
        $reply->user()->associate($user);
        $reply->message = $input['message'];
        $reply->save();

        foreach ($conversation->users as $u) {
            if ($u->id != $user->id) {
                $receipt = new Receipt();
                $receipt->user()->associate($u);
                $receipt->reply()->associate($reply);
                $receipt->save();
            }
        }

        return $reply;
    }

    public function createConversation($owner, $participants, $input)
    {
        $conversation = null;

        Validator::make($input, Conversation::getValidationRules()['create'])->validate();
        Validator::make($input, Reply::getValidationRules()['create'])->validate();

        if ($owner instanceof User) {
            $conversation = $owner->owned_conversations()->create($input);
        }
        
        if ($conversation) {
            foreach ($participants as $participant) {
                $conversation->addUser($participant);
            }

            $this->createReply($conversation, $owner, $input);
        }

        return $conversation;
    }

    public function deleteConversationForUser(User $user)
    {
        $user->receipts()->each(function ($receipt) {
            $receipt->delete();
        });

        $user->replies()->each(function ($reply) {
            $reply->delete();
        });

        $user->participated_conversations()->detach();

        $user->owned_conversations()->each(function ($conversation) {
            $conversation->delete();
        });
    }
}
