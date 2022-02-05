<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Matcher\Database\Seeders\LanguageSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LanguageSeeder::class);
        $this->call(PagesSeeder::class);
    }
}
