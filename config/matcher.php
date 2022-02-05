<?php

return [
    'url' => 'groups/',
    'middleware' => ['auth', 'verified'],
    'max_limit' => env('MATCHER_MAX_LIMIT', 20),
    'default_limit' => env('MATCHER_DEFAULT_LIMIT', 6),
    'image' => [
        'min_upload_width' => env('MATCHER_IMAGE_MIN_UPLOAD_WIDTH', 900),
        'min_upload_height' => env('MATCHER_IMAGE_MIN_UPLOAD_HEIGHT', 300),
        'max_upload_file' => env('MATCHER_IMAGE_MAX_UPLOAD_FILE_SIZE', 1024*5),
    ],
    'peergroups_per_page' => env('MATCHER_PEERGROUPS_PER_PAGE', 20),
    'tags' => [
        'search' => [
            'limit' => env('TAGS_SEARCH_LIMIT', 5),
        ]
    ]
];