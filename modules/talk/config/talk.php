<?php

return [
    'url' => 'conversations/',
    'middleware' => ['auth', 'verified'],
    'replies_per_page' => env('TALK_REPLIES_PER_PAGE', 20),
];