<?php

use App\Http\Controllers\HeartbeatController;
use App\Http\Controllers\Profile\NotificationsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function () {
    Route::get('/', [NotificationsController::class, 'index'])->name('index');
});

Route::get('/heartbeat/badges/', [HeartbeatController::class, 'badges'])->name('heartbeat.badges');