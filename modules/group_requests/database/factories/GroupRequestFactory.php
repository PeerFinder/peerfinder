<?php

namespace GroupRequests\Database\Factories;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Nette\NotImplementedException;
use GroupRequests\Models\GroupRequest;

class GroupRequestFactory extends Factory
{
    protected $model = GroupRequest::class;

    public function definition()
    {
        return [
            'title' => $this->faker->text(100),
            'description' => $this->faker->text(500),
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