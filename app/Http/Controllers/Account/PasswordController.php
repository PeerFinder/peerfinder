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
        return view('frontend.account.password.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request, UpdateUserPassword $updater)
    {
        $updater->update($request->user(), $request->all());
        return redirect()->back()->with('success', __('account/password.password_changed_successfully'));
    }
}
