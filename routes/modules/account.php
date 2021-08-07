<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::group(['prefix' => 'account', 'as' => 'account.', 'middleware' => ['auth', 'verified']], function () {
    Route::group(['prefix' => 'password', 'as' => 'password.'], function () {
        Route::get('/', 'PasswordController@edit')->name('edit');
        Route::put('/update', 'PasswordController@update')->name('update');
    });
    Route::group(['prefix' => 'email', 'as' => 'email.'], function () {
        Route::get('/', 'EmailController@edit')->name('edit');
        Route::put('/update', 'EmailController@update')->name('update');
    });    
});
