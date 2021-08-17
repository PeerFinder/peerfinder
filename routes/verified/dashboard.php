<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
    Route::get('/', function () {
        return view('frontend.dashboard.index');
    })->name('index');
});