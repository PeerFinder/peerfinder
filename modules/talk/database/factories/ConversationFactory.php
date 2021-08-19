<?php

namespace Talk\Database\Factories;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Nette\NotImplementedException;
use Talk\Models\Conversation;

class ConversationFactory extends Factory
{
    protected $model = Conversation::class;

    public function definition()
    {
        return [
            'title' => $this->faker->text(100),
            'body' => $this->faker->text(300),
        ];
    }

    public function byUser(User $user = null)
    {
        return $this->state(function ($attributes) use ($user) {
            $user = $user ?: User::factory()->create();

            return [
                'conversationable_type' => User::class,
                'conversationable_id' => $user->id,
            ];
        });
    }

    public function byCircle()
    {
        return $this->state(function ($attributes) {
            throw new Exception('Not implemented');
        });
    }
}