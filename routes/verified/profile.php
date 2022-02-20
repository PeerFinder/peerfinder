<?php

use App\Http\Controllers\Profile\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'profile.'], function () {
    Route::group(['prefix' => 'u', 'as' => 'user.', 'controller' => UserController::class], function () {
        Route::get('/', 'index')->name('index');
        Route::get('/search', 'search')->name('search');
        Route::get('/{user:username}', 'show')->name('show');
    });
});