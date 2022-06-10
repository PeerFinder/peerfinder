<?php

use App\Http\Controllers\InfocardsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'infocards', 'as' => 'infocards.'], function () {
    Route::post('{slug}/close', [InfocardsController::class, 'close'])->name('close');
});