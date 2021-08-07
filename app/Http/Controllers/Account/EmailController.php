<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EmailController extends Controller
{
    public function edit(Request $request)
    {
        return view('frontend.account.email.edit', [
            'email' => $request->user()->email
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $input = $request->all();

        Validator::make($input, [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ])->validate();

        if ($input['email'] !== $user->email) {

            $user->forceFill([
                'email' => $input['email'],
                'email_verified_at' => null,
            ])->save();
    
            $user->sendEmailVerificationNotification();

            return redirect(route('verification.notice'))->with('success', __('account/email.email_changed_successfully'));
        }

        return redirect()->back()->with('success', __('account/email.email_changed_successfully'));
    }
}
