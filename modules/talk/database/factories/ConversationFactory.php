<?php

namespace Talk\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
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
}