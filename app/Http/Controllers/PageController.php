<?php

namespace App\Http\Controllers;

use App\Helpers\Facades\Pages;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(Request $request, $language, $slug)
    {
        $page = Pages::getPage($language, $slug);

        if (!$page) {
            abort(404);
        }

        return view('frontend.pages.show', $page);
    }
}
