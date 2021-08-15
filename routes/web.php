<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/logout', function() {
    Auth::logout();
    return redirect('/');
})->name('logout');

/* TEMP TEMP TEMP TEMP TEMP */
Route::get('/', function() {
    return 'index';
})->name('index');

/* TEMP TEMP TEMP TEMP TEMP */

/* TEMP TEMP TEMP TEMP TEMP */
Route::get('/secret', function() {
    return 'Secret';
})->middleware(['auth', 'password.confirm']);



/* TEMP TEMP TEMP TEMP TEMP */
Route::get('/info/{language}/{slug}', function($language, $slug) {
    return 'Content of '.$slug;
})->name('info');

/* TEMP TEMP TEMP TEMP TEMP */
Route::group(['prefix' => '/profile', 'as' => 'profile.', 'middleware' => 'auth'], function () {
    Route::get('/', fn() => 'Profile')->name('index');
});