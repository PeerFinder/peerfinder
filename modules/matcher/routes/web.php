<?php

use Illuminate\Support\Facades\Route;
use Matcher\Http\Controllers\AppointmentsController;
use Matcher\Http\Controllers\BookmarksController;
use Matcher\Http\Controllers\GroupTypesController;
use Matcher\Http\Controllers\MembershipsController;
use Matcher\Http\Controllers\PeergroupsController;
use Matcher\Http\Controllers\ImageController;

Route::get('/', [PeergroupsController::class, 'index'])->name('index');

Route::get('/create', [PeergroupsController::class, 'create'])->name('create');
Route::put('/create', [PeergroupsController::class, 'store'])->name('store');

Route::get('/group-types', [GroupTypesController::class, 'index'])->name('group_types');
Route::get('/tags/search', [PeergroupsController::class, 'searchTags'])->withoutMiddleware(['auth', 'verified'])->name('tags.search');

Route::get('/{pg:groupname}', [PeergroupsController::class, 'show'])->withoutMiddleware(['auth', 'verified'])->name('show');
Route::get('/{groupname}/preview', [PeergroupsController::class, 'preview'])->withoutMiddleware(['auth', 'verified'])->name('preview');

Route::group(['prefix' => '/{pg:groupname}'], function () {
    Route::get('/edit', [PeergroupsController::class, 'edit'])->name('edit');
    Route::put('/edit', [PeergroupsController::class, 'update'])->name('update');
    Route::post('/complete', [PeergroupsController::class, 'complete'])->name('complete');
    Route::get('/transfer-ownership', [PeergroupsController::class, 'editOwner'])->name('editOwner');
    Route::put('/transfer-ownership', [PeergroupsController::class, 'updateOwner'])->name('updateOwner');
    Route::get('/delete', [PeergroupsController::class, 'delete'])->name('delete');
    Route::delete('/delete', [PeergroupsController::class, 'destroy'])->name('destroy');

    Route::group(['as' => 'membership.', 'prefix' => 'memberships'], function () {
        Route::get('/', [MembershipsController::class, 'index'])->name('index');
        Route::get('/create', [MembershipsController::class, 'create'])->name('create');
        Route::put('/create', [MembershipsController::class, 'store'])->name('store');
        Route::get('/edit', [MembershipsController::class, 'edit'])->name('edit');
        Route::put('/update', [MembershipsController::class, 'update'])->name('update');
        Route::get('/delete/{username?}', [MembershipsController::class, 'delete'])->name('delete');
        Route::delete('/delete/{username?}', [MembershipsController::class, 'destroy'])->name('destroy');
        Route::post('/approve/{username}', [MembershipsController::class, 'approve'])->name('approve');
        Route::post('/decline/{username}', [MembershipsController::class, 'decline'])->name('decline');
        Route::put('/manage', [MembershipsController::class, 'manage'])->name('manage');
    });

    Route::group(['as' => 'bookmarks.', 'prefix' => 'bookmarks'], function () {
        Route::get('/edit', [BookmarksController::class, 'edit'])->name('edit');
        Route::put('/update', [BookmarksController::class, 'update'])->name('update');
    });

    Route::group(['as' => 'appointments.', 'prefix' => 'appointments'], function () {
        Route::get('/', [AppointmentsController::class, 'index'])->name('index');
        Route::get('/create', [AppointmentsController::class, 'create'])->name('create');
        Route::put('/create', [AppointmentsController::class, 'store'])->name('store');
        Route::get('/{appointment:identifier}', [AppointmentsController::class, 'show'])->name('show');
        Route::get('/{appointment:identifier}/ical', [AppointmentsController::class, 'download'])->name('download');
        Route::get('/{appointment:identifier}/edit', [AppointmentsController::class, 'edit'])->name('edit');
        Route::put('/{appointment:identifier}/edit', [AppointmentsController::class, 'update'])->name('update');
        Route::delete('/{appointment:identifier}/delete', [AppointmentsController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'image', 'as' => 'image.'], function () {
        Route::get('/edit', [ImageController::class, 'edit'])->name('edit');
        Route::put('/update', [ImageController::class, 'update'])->name('update');
        Route::delete('/destroy', [ImageController::class, 'destroy'])->name('destroy');
    });
});


