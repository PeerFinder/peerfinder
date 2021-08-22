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
        return $this->filterUsers($conversation->users->all());
    }

    public function usersAsString($users, $with_links = false)
    {
        if ($with_links) {
            return implode(", ", array_map(fn($user) => '<a href="' . $user->profileUrl() . '">' . $user->name . '</a>', $users));
        }

        return implode(", ", array_map(fn($user) => $user->name, $users));
    }

    public function createReply($parent, User $user, $input)
    {
        Validator::make($input, Reply::rules()['create'])->validate();

        $reply = new Reply();

        if ($parent instanceof Conversation) {
            $conversation = $parent;
        }

        if ($parent instanceof Reply) {
            $conversation = $parent->conversation()->first();
            $reply->parent()->associate($parent);
        }

        $reply->conversation()->associate($conversation);
        $reply->user()->associate($user);
        $reply->message = $input['message'];
        $reply->save();

        $conversation->touch();

        foreach ($conversation->users as $u) {
            if ($u->id != $user->id) {
                Receipt::firstOrCreate(['user_id' => $u->id, 'conversation_id' => $conversation->id]);
            }
        }

        return $reply;
    }

    public function createConversation($owner, $participants, $input)
    {
        $conversation = null;

        Validator::make($input, Conversation::rules()['create'])->validate();
        Validator::make($input, Reply::rules()['create'])->validate();

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

    public function checkConversationCreation($user)
    {
        if ($user->id == auth()->user()->id) {
            return redirect(route('talk.index'));
        }
        
        $conversation = Conversation::forUsers([auth()->user(), $user])->get()->first();

        if ($conversation) {
            return redirect(route('talk.show', ['conversation' => $conversation->identifier]));
        }

        return null;
    }

    public function userHasUnreadConversations(User $user)
    {
        return $user->receipts()->exists();
    }

    public function getRecentUnreadConversationForUser(User $user)
    {
        $receipt = Receipt::where('user_id', $user->id)->orderBy('updated_at', 'desc')->first();

        if ($receipt) {
            return $receipt->conversation;
        } else {
            return null;
        }
    }

    public function embedConversation(Conversation $conversation)
    {
        return view('talk::conversations.embedded.show', [
            'conversation' => $conversation,
        ]);
    }
}
