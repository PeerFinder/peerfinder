<?php

use GroupRequests\Http\Controllers\GroupRequestsController;
use Illuminate\Support\Facades\Route;

Route::group(['controller' => GroupRequestsController::class], function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::put('/create', 'store')->name('store');
    Route::get('/{identifier}/edit', 'edit')->name('edit');
});