<?php

use Illuminate\Support\Facades\Route;
use Talk\Facades\Talk;

Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
    Route::get('/', function () {
        return view('frontend.dashboard.index');
    })->name('index');
});