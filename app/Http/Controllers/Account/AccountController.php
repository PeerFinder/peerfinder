<?php

namespace App\Http\Controllers\Account;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    use PasswordValidationRules;
    
    public function edit(Request $request)
    {
        return view('frontend.account.account.edit', [
            'user' => $request->user(),
        ]);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        $input = $request->all();

        if ($user->ownsPeergroups()) {
            return back()->withErrors(__('account/account.owning_peergroups_warning'));
        }

        Validator::make($input, [
            'password' => ['required', 'string'],
        ])->after(function ($validator) use ($user, $input) {
            if (!Hash::check($input['password'], $user->password)) {
                $validator->errors()->add('password', __('The provided password does not match your current password.'));
            }
        })->validate();

        Auth::logout();

        $user->delete();

        return redirect()->route('index')->with('success', __('account/account.account_deleted_message'));
    }
}
