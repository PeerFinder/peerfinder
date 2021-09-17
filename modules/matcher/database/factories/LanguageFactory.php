<?php

namespace Matcher\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Matcher\Models\Language;

class LanguageFactory extends Factory
{
    protected $model = Language::class;

    public function definition()
    {
        $code = $this->faker->languageCode();
        
        return [
            'title' => $code,
            'code' => $code,
        ];
    }
}