<?php

use Illuminate\Support\Facades\Route;
use Matcher\Http\Controllers\AppointmentsController;
use Matcher\Http\Controllers\BookmarksController;
use Matcher\Http\Controllers\GroupTypesController;
use Matcher\Http\Controllers\MembershipsController;
use Matcher\Http\Controllers\PeergroupsController;
use Matcher\Http\Controllers\ImageController;
use Matcher\Http\Controllers\InvitationsController;

Route::get('/group-types', [GroupTypesController::class, 'index'])->name('group_types');

Route::controller(PeergroupsController::class)->group(function() {
    Route::get('/', 'index')->name('index');

    Route::get('/create', 'create')->name('create');
    Route::put('/create', 'store')->name('store');

    Route::get('/tags/search', 'searchTags')->withoutMiddleware(['auth', 'verified'])->name('tags.search');

    Route::get('/{pg:groupname}', 'show')->withoutMiddleware(['auth', 'verified'])->name('show');
    Route::get('/{groupname}/preview', 'preview')->withoutMiddleware(['auth', 'verified'])->name('preview');
});

Route::group(['prefix' => '/{pg:groupname}'], function () {
    Route::controller(PeergroupsController::class)->group(function() {
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/edit', 'update')->name('update');
        Route::post('/complete', 'complete')->name('complete');
        Route::get('/transfer-ownership', 'editOwner')->name('editOwner');
        Route::put('/transfer-ownership', 'updateOwner')->name('updateOwner');
        Route::get('/delete', 'delete')->name('delete');
        Route::delete('/delete', 'destroy')->name('destroy');
    });

    Route::group(['as' => 'membership.', 'prefix' => 'memberships', 'controller' => MembershipsController::class], function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::put('/create', 'store')->name('store');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
        Route::get('/delete/{username?}', 'delete')->name('delete');
        Route::delete('/delete/{username?}', 'destroy')->name('destroy');
        Route::post('/approve/{username}', 'approve')->name('approve');
        Route::post('/decline/{username}', 'decline')->name('decline');
        Route::put('/manage', 'manage')->name('manage');
    });

    Route::group(['as' => 'bookmarks.', 'prefix' => 'bookmarks', 'controller' => BookmarksController::class], function () {
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
    });

    Route::group(['as' => 'appointments.', 'prefix' => 'appointments', 'controller' => AppointmentsController::class], function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::put('/create', 'store')->name('store');
        Route::get('/{appointment:identifier}', 'show')->name('show');
        Route::get('/{appointment:identifier}/ical', 'download')->name('download');
        Route::get('/{appointment:identifier}/edit', 'edit')->name('edit');
        Route::put('/{appointment:identifier}/edit', 'update')->name('update');
        Route::delete('/{appointment:identifier}/delete', 'destroy')->name('destroy');
    });

    Route::group(['prefix' => 'image', 'as' => 'image.', 'controller' => ImageController::class], function () {
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
        Route::delete('/destroy', 'destroy')->name('destroy');
    });

    Route::group(['as' => 'invitations.', 'prefix' => 'invitations', 'controller' => InvitationsController::class], function () {
        Route::get('/create', 'create')->name('create');
        Route::put('/create', 'store')->name('store');
        Route::delete('/delete', 'destroy')->name('destroy');
    });
});


