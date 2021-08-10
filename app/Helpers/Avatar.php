<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class Avatar
{
    private $min_upload_size = 0;
    private $max_upload_file = 0;

    public function __construct()
    {
        $this->min_upload_size = config('user.avatar.min_upload_size');
        $this->max_upload_file = config('user.avatar.max_upload_file');
    }

    public function setForUser(User $user, Request $request)
    {
        $request->validate([
            'avatar' => sprintf(
                'required|image|max:%d|dimensions:min_width=%d,min_height=%d',
                $this->max_upload_file,
                $this->min_upload_size,
                $this->min_upload_size
            ),
        ]);

        if ($user->avatar) {
            $newFileName = $user->avatar;
        } else {
            $newFileName = Str::uuid() . '.jpg';
        }
        
        $image = Image::make($request->file('avatar'));

        Storage::disk('local')->put('avatars/' . $newFileName, (string) $image->encode('jpg'));

        $user->avatar = $newFileName;

        $user->save();
    }

    public function deleteForUser(User $user)
    {
        if ($user->avatar) {
            Storage::disk('local')->delete('avatars/' . $user->avatar);
            $user->avatar = null;
            $user->save();
        }
    }
}