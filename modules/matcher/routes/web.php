<?php

use Illuminate\Support\Facades\Route;
use Matcher\Http\Controllers\AppointmentsController;
use Matcher\Http\Controllers\BookmarksController;
use Matcher\Http\Controllers\MembershipsController;
use Matcher\Http\Controllers\PeergroupsController;

Route::get('/', [PeergroupsController::class, 'index'])->name('index');

Route::get('/create', [PeergroupsController::class, 'create'])->name('create');
Route::put('/create', [PeergroupsController::class, 'store'])->name('store');

Route::group(['prefix' => '/{pg:groupname}'], function () {
    Route::get('/', [PeergroupsController::class, 'show'])->name('show');
    Route::get('/edit', [PeergroupsController::class, 'edit'])->name('edit');
    Route::put('/edit', [PeergroupsController::class, 'update'])->name('update');
    Route::post('/complete', [PeergroupsController::class, 'complete'])->name('complete');
    Route::get('/transfer-ownership', [PeergroupsController::class, 'editOwner'])->name('editOwner');
    Route::put('/transfer-ownership', [PeergroupsController::class, 'updateOwner'])->name('updateOwner');
    Route::get('/delete', [PeergroupsController::class, 'delete'])->name('delete');
    Route::delete('/delete', [PeergroupsController::class, 'destroy'])->name('destroy');

    Route::group(['as' => 'membership.', 'prefix' => 'membership'], function () {
        Route::get('/create', [MembershipsController::class, 'create'])->name('create');
        Route::put('/create', [MembershipsController::class, 'store'])->name('store');
        Route::get('/edit', [MembershipsController::class, 'edit'])->name('edit');
        Route::put('/update', [MembershipsController::class, 'update'])->name('update');
        Route::get('/delete', [MembershipsController::class, 'delete'])->name('delete');
        Route::delete('/delete', [MembershipsController::class, 'destroy'])->name('destroy');
        Route::post('/{username}/approve', [MembershipsController::class, 'approve'])->name('approve');
        Route::post('/{username}/decline', [MembershipsController::class, 'decline'])->name('decline');
    });

    Route::group(['as' => 'bookmarks.', 'prefix' => 'bookmarks'], function () {
        Route::get('/edit', [BookmarksController::class, 'edit'])->name('edit');
        Route::put('/update', [BookmarksController::class, 'update'])->name('update');
    });

    Route::group(['as' => 'appointments.', 'prefix' => 'appointments'], function () {
        Route::get('/create', [AppointmentsController::class, 'create'])->name('create');
        Route::put('/create', [AppointmentsController::class, 'store'])->name('store');
        Route::get('/{appointment:identifier}', [AppointmentsController::class, 'show'])->name('show');
        Route::get('/{appointment:identifier}/edit', [AppointmentsController::class, 'edit'])->name('edit');
        Route::put('/{appointment:identifier}/edit', [AppointmentsController::class, 'update'])->name('update');
        Route::delete('/{appointment:identifier}/delete', [AppointmentsController::class, 'destroy'])->name('destroy');
    });
});


