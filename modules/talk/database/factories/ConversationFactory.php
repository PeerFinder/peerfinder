<?php

namespace Talk\Database\Factories;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Matcher\Models\Peergroup;
use Nette\NotImplementedException;
use Talk\Models\Conversation;

class ConversationFactory extends Factory
{
    protected $model = Conversation::class;

    public function definition()
    {
        return [
            'title' => $this->faker->text(100),
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

    public function byPeergroup(Peergroup $pg)
    {
        return $this->state(function ($attributes) use ($pg) {
            return [
                'conversationable_type' => Peergroup::class,
                'conversationable_id' => $pg->id,
            ];
        });
    }
}