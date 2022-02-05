<?php

use App\Http\Controllers\Profile\DashboardController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
});