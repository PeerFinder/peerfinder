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
            'title_de' => $this->faker->realText(20),
            'title_en' => $this->faker->realText(20),
            'description_de' => $this->faker->text(),
            'description_en' => $this->faker->text(),
        ];
    }

    public function withParent(GroupType $gt)
    {
        return $this->state(function ($attributes) use ($gt) {
            return [
                'group_type_id' => $gt->id,
            ];
        });
    }    
}