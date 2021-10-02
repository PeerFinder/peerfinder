<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Page::firstOrCreate([
            'slug' => 'terms-of-service',
            'title_de' => "Nutzungsbedingungen",
            'title_en' => "Terms of service",
            'body_de' => "Inhalt der Nutzungsbedingungen",
            'body_en' => "Content of Terms of service",
        ]);

        Page::firstOrCreate([
            'slug' => 'privacy-policy',
            'title_de' => "Datenschutz",
            'title_en' => "Privacy policy",
            'body_de' => "Inhalt von Datenschutz",
            'body_en' => "Content of Privacy policy",
        ]);

        Page::firstOrCreate([
            'slug' => 'imprint',
            'title_de' => "Impressum",
            'title_en' => "Imprint",
            'body_de' => "Inhalt von Impressum",
            'body_en' => "Content of Imprint",
        ]);
    }
}
