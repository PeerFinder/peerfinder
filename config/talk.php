<?php

return [
    'url' => [
        'web' => 'conversations/',
        'api' => 'conversations/api/',
    ],
    'middleware' => [
        'web' => ['auth', 'verified'],
        'api' => [],
    ],
    'replies_per_page' => env('TALK_REPLIES_PER_PAGE', 20),
    'min_receipt_age' => env('TALK_MIN_RECEIPT_AGE', 60),
    'receipts_batch_limit' => env('TALK_RECEIPTS_BATCH_LIMIT', 1),
];