<?php

return [
    'url' => 'groups/',
    'middleware' => ['auth', 'verified'],
    'max_limit' => env('MATCHER_MAX_LIMIT', 20),
];