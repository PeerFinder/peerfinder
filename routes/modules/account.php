<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
    Route::get('/', function () {
        return redirect(route('account.profile.edit'));
    })->name('index');

    Route::group(['prefix' => 'password', 'as' => 'password.'], function () {
        Route::get('/', 'PasswordController@edit')->name('edit');
        Route::put('/update', 'PasswordController@update')->name('update');
    });

    Route::group(['prefix' => 'email', 'as' => 'email.'], function () {
        # This routes are accessible even by not verified users so they can change their mail to be able to verify it
        Route::get('/', 'EmailController@edit')->name('edit')->withoutMiddleware('verified');
        Route::put('/update', 'EmailController@update')->name('update')->withoutMiddleware('verified');
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

    Route::group(['prefix' => 'avatar', 'as' => 'avatar.'], function () {
        Route::get('/', 'AvatarController@edit')->name('edit');
        Route::get('/show/{user:id}', 'AvatarController@show')->name('show');
        Route::put('/update', 'AvatarController@update')->name('update');
        Route::delete('/destroy', 'AvatarController@destroy')->name('destroy');
    });
});
