<?php

use App\Http\Controllers\Support\WishlistController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'support', 'as' => 'support.'], function () {
    Route::group(['prefix' => 'wishlist', 'as' => 'wishlist.', 'controller' => WishlistController::class], function () {
        Route::get('/create', 'create')->name('create');
        Route::put('/create', 'store')->name('store');        
    });
});