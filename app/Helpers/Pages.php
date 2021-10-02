<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use App\Models\Page;

class Pages
{
    public function getPage($language, $slug)
    {
        if(!in_array($language, config('app.available_locales'))) {
            return null;
        }
        
        $page = Page::whereSlug($slug)->first();

        if (!$page) {
            return null;
        }

        $titleField = 'title_' . $language;
        $bodyField = 'body_' . $language;

        return [
            'title' => $page->$titleField,
            'body' => $page->$bodyField,
        ];
    }
}