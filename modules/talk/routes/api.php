<?php

use Illuminate\Support\Facades\Route;
use Talk\Facades\Talk;

Route::get('/process-receipts', function () {
    Talk::sendNotificationsForReceipts();
    return "OK";
})->name('api.process-receipts');