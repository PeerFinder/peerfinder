<?php

use App\Http\Controllers\HomepageController;
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

Route::get('/logout', function() {
    Auth::logout();
    return redirect('/');
})->name('logout');

Route::get('/pages/{language}/{slug}', [PageController::class, 'show'])->name('page.show');

Route::get('/{language}', [HomepageController::class, 'show'])->where('language', '[a-z]{2}')->name('homepage.show');
Route::get('/', [HomepageController::class, 'index'])->name('index');