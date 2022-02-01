<?php

namespace Matcher\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Matcher\Models\Peergroup;

class PeergroupFactory extends Factory
{
    protected $model = Peergroup::class;

    public function definition()
    {
        return [
            'title' => $this->faker->realText(50),
            'description' => $this->faker->text(),
            'limit' => $this->faker->numberBetween(6, config('matcher.max_limit')),
            'begin' => $this->faker->date(),
            'virtual' => false,
            'private' => false,
            'open' => true,
            'with_approval' => false,
            'location' => $this->faker->city(),
            'meeting_link' => $this->faker->url(),
        ];
    }

    public function byUser(User $user = null)
    {
        return $this->state(function ($attributes) use ($user) {
            $user = $user ?: User::factory()->create();

            return [
                'user_id' => $user->id,
            ];
        });
    }
}