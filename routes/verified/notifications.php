<?php

use App\Http\Controllers\Profile\NotificationsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function () {
    Route::get('/', [NotificationsController::class, 'index'])->name('index');
});