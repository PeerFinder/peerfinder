<?php

namespace Talk\Database\Factories;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Talk\Models\Conversation;
use Talk\Models\Reply;

class ReplyFactory extends Factory
{
    protected $model = Reply::class;

    public function definition()
    {
        return [
            'message' => $this->faker->text(100),
        ];
    }

    public function forConversation(Conversation $conversation)
    {
        return $this->state(function ($attributes) use ($conversation) {
            return [
                'conversation_id' => $conversation->id,
            ];
        });
    }

    public function byUser(User $user)
    {
        return $this->state(function ($attributes) use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }    
}