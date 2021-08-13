<?php

use App\Http\Controllers\Profile\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'profile.'], function () {
    Route::group(['prefix' => 'u', 'as' => 'user.'], function () {
        Route::get('/{user:username}', [UserController::class, 'show'])->name('show');
    });
});