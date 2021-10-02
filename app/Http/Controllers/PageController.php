<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(Request $request, $language, $slug)
    {
        if(!in_array($language, config('app.available_locales'))) {
            abort(404);
        }
        
        $page = Page::whereSlug($slug)->firstOrFail();

        $titleField = 'title_' . $language;
        $bodyField = 'body_' . $language;

        $title = $page->$titleField;
        $body = $page->$bodyField;

        return view('frontend.pages.show', compact('title', 'body'));
    }
}
