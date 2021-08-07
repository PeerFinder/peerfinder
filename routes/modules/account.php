<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::group(['prefix' => 'account', 'as' => 'account.', 'middleware' => ['auth', 'verified']], function () {
    Route::group(['prefix' => 'password', 'as' => 'password.'], function () {
        Route::get('/', 'PasswordController@edit')->name('edit');
        Route::put('/update', 'PasswordController@update')->name('update');
    });
});
