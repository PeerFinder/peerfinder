<?php

namespace App\Http\Controllers;

use App\Helpers\Facades\Infocards;
use Illuminate\Http\Request;

class InfocardsController extends Controller
{
    public function close(Request $request, $slug)
    {
        $ret = Infocards::closeCard(app()->getLocale(), $slug, auth()->user());

        return response()->json(['closed' => $ret]);
    }
}
