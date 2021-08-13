<?php

return [
    'avatar' => [
        'size_default' => env('AVATAR_SIZE_DEFAULT', 100),
        'size_min' => env('AVATAR_SIZE_MIN', 10),
        'size_max' => env('AVATAR_SIZE_MAX', 500),
        'min_upload_size' => env('AVATAR_MIN_UPLOAD_SIZE', 300),
        'max_upload_file' => env('AVATAR_MAX_UPLOAD_FILE_SIZE', 1024*5),
    ],
];