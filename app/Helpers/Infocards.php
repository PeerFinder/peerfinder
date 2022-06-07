<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use App\Models\Infocard;
use Illuminate\Support\Facades\DB;

class Infocards
{
    public function getCard($language, $slug, $user = null)
    {
        if(!in_array($language, config('app.available_locales'))) {
            return null;
        }
        
        $card = Infocard::whereSlug($slug)
                    ->whereLanguage($language)
                    ->whereActive(true)
                    ->first();

        if ($card && $user) {
            if ($this->isCardClosed($card, $user)) {
                return null;
            }
        }

        return $card;
    }
    
    public function getCards($language, $slugs, $user = null)
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

    public function closeCard($language, $slug, $user = null)
    {
        $card = $this->getCard($language, $slug, $user);

        if (!$card) {
            return false;
        }

        DB::table('infocards_closed')->insert([
            'user_id' => $user->id,
            'infocard_id' => $card->id,
        ]);

        return true;
    }

    public function isCardClosed($card, $user)
    {
        $closed = DB::table('infocards_closed')
                    ->where('infocard_id', '=', $card->id)
                    ->where('user_id', '=', $user->id)
                    ->first();

        return $closed != null;
    }
}