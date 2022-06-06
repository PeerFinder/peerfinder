<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use App\Models\Infocard;

class Infocards
{
    public function getCard($language, $slug, $user)
    {
        if(!in_array($language, config('app.available_locales'))) {
            return null;
        }
        
        $card = Infocard::whereSlug($slug)
                    ->whereLanguage($language)
                    ->whereActive(true)
                    ->first();

        // #TODO: Check if the card was hidden by the user

        return $card;
    }
    
    public function getCards($language, $slugs, $user)
    {
        $cards = [];

        foreach ($slugs as $slug) {
            $card = $this->getCard($language, $slug, $user);

            if ($card) {
                $cards[$slug] = $card;
            }
        }

        return $cards;
    }

    public function markdown($markdown)
    {
        return Str::markdown($markdown);
    }
}