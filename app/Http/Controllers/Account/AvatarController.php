<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Facades\Avatar;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class AvatarController extends Controller
{
    public function edit(Request $request)
    {
        return "";
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
