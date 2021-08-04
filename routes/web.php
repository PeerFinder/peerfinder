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

Route::get('/', function() {
    return 'index';
});

Route::get('/logout', function() {
    Auth::logout();
    return redirect('/');
});

Route::get('/info/{slug}', function($slug) {
    return 'Content of '.$slug;
})->name('info');

Route::group(['prefix' => '/profile', 'as' => 'profile.', 'middleware' => 'auth'], function () {
    Route::get('/', fn() => 'Profile')->name('index');
});