<?php

use App\Http\Controllers\Account\PasswordController;
use App\Http\Controllers\Account\EmailController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Account\AvatarController;
use App\Http\Controllers\Account\SettingsController;
use App\Http\Controllers\Account\NotificationSettingsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
    Route::get('/', function () {
        return redirect(route('account.profile.edit'));
    })->name('index');

    Route::group(['prefix' => 'password', 'as' => 'password.', 'controller' => PasswordController::class], function () {
        Route::get('/', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
    });

    Route::group(['prefix' => 'email', 'as' => 'email.', 'controller' => EmailController::class], function () {
        # This routes are accessible even by not verified users so they can change their mail to be able to verify it
        Route::get('/', 'edit')->name('edit')->withoutMiddleware('verified');
        Route::put('/update', 'update')->name('update')->withoutMiddleware('verified');
    });

    Route::group(['prefix' => 'profile', 'as' => 'profile.', 'controller' => ProfileController::class], function () {
        Route::get('/', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
    });

    Route::group(['prefix' => 'account', 'as' => 'account.', 'controller' => AccountController::class], function () {
        Route::get('/', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
        Route::delete('/destroy', 'destroy')->name('destroy');
    });

    Route::group(['prefix' => 'avatar', 'as' => 'avatar.', 'controller' => AvatarController::class], function () {
        Route::get('/', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
        Route::delete('/destroy', 'destroy')->name('destroy');
    });

    Route::group(['prefix' => 'settings', 'as' => 'settings.', 'controller' => SettingsController::class], function () {
        Route::get('/', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
    });

    Route::group(['prefix' => 'notifications', 'as' => 'notification_settings.', 'controller' => NotificationSettingsController::class], function () {
        Route::get('/', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
    });
});
