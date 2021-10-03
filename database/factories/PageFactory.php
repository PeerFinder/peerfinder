<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'slug' => $this->faker->slug(),
            'title_de' => $this->faker->text(10),
            'title_en' => $this->faker->text(10),
            'body_de' => $this->faker->text(50),
            'body_en' => $this->faker->text(50),
        ];
    }
}
