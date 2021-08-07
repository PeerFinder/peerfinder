<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('frontend.account.profile.edit', [
            'user' => $request->user()
        ]);        
    }

    public function update(Request $request)
    {
        $input = $request->all();

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ])->validate();

        $request->user()->update($input);

        return redirect()->back()->with('success', __('account/profile.profile_changed_successfully'));
    }
}
