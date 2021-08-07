<?php

namespace App\Http\Controllers\Account;

use App\Actions\Fortify\PasswordValidationRules;
use App\Actions\Fortify\UpdateUserPassword;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    use PasswordValidationRules;

    public function edit(Request $request)
    {
        return view('frontend.account.password.edit');
    }

    public function update(Request $request, UpdateUserPassword $updater)
    {
        $updater->update($request->user(), $request->all());

        return redirect()->back()->with('success', __('account/password.password_changed_successfully'));

        /*
        $user = $request->user();
        $input = $request->all();

        Validator::make($input, [
            'current_password' => ['required', 'string'],
            'password' => $this->passwordRules(),
        ])->after(function ($validator) use ($user, $input) {
            if (! isset($input['current_password']) || ! Hash::check($input['current_password'], $user->password)) {
                $validator->errors()->add('current_password', __('The provided password does not match your current password.'));
            }
        })->validate();

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();

        return redirect()->back()->with('success', __('account/password.password_changed_successfully'));*/
    }
}
