<?php

use App\Http\Controllers\HomepageController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PageController;
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

Route::group(['prefix' => 'media', 'as' => 'media.'], function () {
    Route::get('/avatar/{user}_{size}_nocache.jpg', [MediaController::class, 'avatar'])->where('size', '[0-9]+')->name('avatar.nocache');
    Route::get('/avatar/{user}_{size}.jpg', [MediaController::class, 'avatar'])->where('size', '[0-9]+')->middleware('cache.headers:private;max_age=300;etag')->name('avatar');
});


Route::get('/logout', function() {
    Auth::logout();
    return redirect('/');
})->name('logout');

Route::get('/pages/{language}/{slug}', [PageController::class, 'show'])->name('page.show');

Route::get('/{language}', [HomepageController::class, 'show'])->where('language', '[a-z]{2}')->name('homepage.show');
Route::get('/', [HomepageController::class, 'index'])->name('index');