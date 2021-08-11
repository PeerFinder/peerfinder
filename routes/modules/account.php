<?php

use App\Http\Controllers\Account\PasswordController;
use App\Http\Controllers\Account\EmailController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Account\AvatarController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
    Route::get('/', function () {
        return redirect(route('account.profile.edit'));
    })->name('index');

    Route::group(['prefix' => 'password', 'as' => 'password.'], function () {
        Route::get('/', [PasswordController::class, 'edit'])->name('edit');
        Route::put('/update', [PasswordController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => 'email', 'as' => 'email.'], function () {
        # This routes are accessible even by not verified users so they can change their mail to be able to verify it
        Route::get('/', [EmailController::class, 'edit'])->name('edit')->withoutMiddleware('verified');
        Route::put('/update', [EmailController::class, 'update'])->name('update')->withoutMiddleware('verified');
    });

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
        Route::get('/', [AccountController::class, 'edit'])->name('edit');
        Route::put('/update', [AccountController::class, 'update'])->name('update');
        Route::delete('/destroy', [AccountController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'avatar', 'as' => 'avatar.'], function () {
        Route::get('/', [AvatarController::class, 'edit'])->name('edit');
        Route::get('/show/{user:username}_{size}.jpg', [AvatarController::class, 'show'])->where('size', '[0-9]+')->name('show');
        Route::put('/update', [AvatarController::class, 'update'])->name('update');
        Route::delete('/destroy', [AvatarController::class, 'destroy'])->name('destroy');
    });
});
