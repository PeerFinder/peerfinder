<?php

namespace App\Http\Controllers;

use App\Helpers\Facades\Pages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class HomepageController extends Controller
{
    public function index(Request $request)
    {
        return redirect(route('homepage.show', ['language' => App::getLocale()]));
    }

    public function show(Request $request, $language)
    {
        $page = Pages::getPage($language, 'homepage');

        if (!$page) {
            abort(404);
        }

        return view('frontend.homepage.index', $page);
    }    
}
