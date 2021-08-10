<?php

return [
    'avatar' => [
        'size' => env('AVATAR_SIZE', 100),
        'min_upload_size' => env('AVATAR_MIN_UPLOAD_SIZE', 200),
        'max_upload_file' => env('AVATAR_MAX_UPLOAD_FILE_SIZE', 1700),
    ],
];