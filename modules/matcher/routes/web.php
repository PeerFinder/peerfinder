<?php

use Illuminate\Support\Facades\Route;
use Matcher\Http\Controllers\MembershipController;
use Matcher\Http\Controllers\PeergroupController;

Route::get('/create', [PeergroupController::class, 'create'])->name('create');
Route::put('/create', [PeergroupController::class, 'store'])->name('store');

Route::group(['prefix' => '/{pg:groupname}'], function () {
    Route::get('/', [PeergroupController::class, 'show'])->name('show');
    Route::get('/edit', [PeergroupController::class, 'edit'])->name('edit');
    Route::put('/edit', [PeergroupController::class, 'update'])->name('update');
    Route::post('/complete', [PeergroupController::class, 'complete'])->name('complete');
    Route::get('/transfer-ownership', [PeergroupController::class, 'editOwner'])->name('editOwner');
    Route::put('/transfer-ownership', [PeergroupController::class, 'updateOwner'])->name('updateOwner');
    Route::get('/delete', [PeergroupController::class, 'delete'])->name('delete');
    Route::delete('/delete', [PeergroupController::class, 'destroy'])->name('destroy');

    Route::group(['as' => 'membership.', 'prefix' => 'membership'], function () {
        Route::get('/create', [MembershipController::class, 'create'])->name('create');
        Route::put('/create', [MembershipController::class, 'store'])->name('store');
        Route::get('/edit', [MembershipController::class, 'edit'])->name('edit');
        Route::put('/update', [MembershipController::class, 'update'])->name('update');
        Route::get('/delete', [MembershipController::class, 'delete'])->name('delete');
        Route::delete('/delete', [MembershipController::class, 'destroy'])->name('destroy');
        Route::post('/{username}/approve', [MembershipController::class, 'approve'])->name('approve');
        Route::post('/{username}/decline', [MembershipController::class, 'decline'])->name('decline');
    });
});


