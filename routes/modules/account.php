<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::group(['prefix' => 'account', 'as' => 'account.', 'middleware' => ['auth', 'verified']], function () {
    Route::get('/', function () {
        return redirect(route('account.profile.edit'));
    })->name('index');

    Route::group(['prefix' => 'password', 'as' => 'password.'], function () {
        Route::get('/', 'PasswordController@edit')->name('edit');
        Route::put('/update', 'PasswordController@update')->name('update');
    });

    Route::group(['prefix' => 'email', 'as' => 'email.'], function () {
        Route::get('/', 'EmailController@edit')->name('edit');
        Route::put('/update', 'EmailController@update')->name('update');
    });

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('/', 'ProfileController@edit')->name('edit');
        Route::put('/update', 'ProfileController@update')->name('update');
    });

    Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
        Route::get('/', 'AccountController@edit')->name('edit');
        Route::put('/update', 'AccountController@update')->name('update');
        Route::delete('/destroy', 'AccountController@destroy')->name('destroy');
    });    
});
