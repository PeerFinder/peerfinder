<?php

use App\Http\Controllers\Profile\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'profile.'], function () {
    Route::group(['prefix' => 'u', 'as' => 'user.'], function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{user:username}', [UserController::class, 'show'])->name('show');
    });
});