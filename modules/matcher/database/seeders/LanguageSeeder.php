<?php

namespace Matcher\Database\Seeders;

use Illuminate\Database\Seeder;
use Matcher\Models\Language;

class LanguageSeeder extends Seeder
{
    public function run()
    {
        $languages = [
            "de" => "Deutsch",
            "en" => "English",
            "fr" => "Français",
            "es" => "Español",
            "it" => "Italiano"
        ];

        foreach ($languages as $code => $title) {
            Language::firstOrCreate(['code' => $code, 'title' => $title]);
        }
    }
}
