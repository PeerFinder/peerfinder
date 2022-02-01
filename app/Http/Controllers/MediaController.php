<?php

namespace App\Http\Controllers;

use App\Helpers\Facades\Avatar;
use App\Models\User;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function avatar($username, Request $request)
    {
        $size = filter_var($request->size, FILTER_VALIDATE_INT, [ 'options' => [
            'default' => config('user.avatar.size_default'),
            'min_range' => config('user.avatar.size_min'),
            'max_range' => config('user.avatar.size_max'),
        ]]);

        if (auth()->check() && auth()->user()->hasVerifiedEmail()) {
            $user = User::whereUsername($username)->firstOrFail();
            return Avatar::forUser($user, $size);
        } else {
            return Avatar::placeholder($size);
        }
    }
}
