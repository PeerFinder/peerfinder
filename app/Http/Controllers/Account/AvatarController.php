<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Facades\Avatar;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;


class AvatarController extends Controller
{
    public function edit(Request $request)
    {
        return view('frontend.account.avatar.edit', [
            'user' => $request->user(),
        ]);
    }

    public function show(User $user, Request $request)
    {
        $size = filter_var($request->size, FILTER_VALIDATE_INT, [ 'options' => [
            'default' => config('user.avatar.size_default'),
            'min_range' => config('user.avatar.size_min'),
            'max_range' => config('user.avatar.size_max'),
        ]]);

        return Avatar::forUser($user, $size);
    }

    public function update(Request $request)
    {
        Avatar::setForUser($request->user(), $request);
        return redirect()->back()->with('success', __('account/avatar.avatar_changed_successfully'));
    }

    public function destroy(Request $request)
    {
        Avatar::deleteForUser($request->user());
        return redirect()->back()->with('success', __('account/avatar.avatar_deleted_successfully'));
    }
}
