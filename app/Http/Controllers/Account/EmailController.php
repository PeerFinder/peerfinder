<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function edit(Request $request)
    {
        return view('frontend.account.email.edit', [
            'email' => $request->user()->email
        ]);
    }
}
