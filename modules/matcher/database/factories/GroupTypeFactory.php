<?php

namespace Matcher\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Matcher\Models\GroupType;

class GroupTypeFactory extends Factory
{
    protected $model = GroupType::class;

    public function definition()
    {
        return [
            'title_de' => $this->faker->text(),
            'title_en' => $this->faker->text(),
            'description_de' => $this->faker->text(),
            'description_en' => $this->faker->text(),
        ];
    }
}