<?php

namespace Database\Factories;

use App\Models\Infocard;
use Illuminate\Database\Eloquent\Factories\Factory;

class InfocardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Infocard::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'slug' => $this->faker->slug(),
            'language' => 'en',
            'title' => $this->faker->text(10),
            'body' => $this->faker->text(50),
            'closable' => false,
            'active' => true,
        ];
    }
}
