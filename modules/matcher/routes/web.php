<?php

use Illuminate\Support\Facades\Route;
use Matcher\Http\Controllers\PeergroupController;

Route::get('/create', [PeergroupController::class, 'create'])->name('create');
Route::put('/create', [PeergroupController::class, 'store'])->name('store');

Route::get('/{pg:groupname}', [PeergroupController::class, 'show'])->name('show');
Route::get('/{pg:groupname}/edit', [PeergroupController::class, 'edit'])->name('edit');
Route::put('/{pg:groupname}/edit', [PeergroupController::class, 'update'])->name('update');

Route::post('/{pg:groupname}/complete', [PeergroupController::class, 'complete'])->name('complete');

Route::get('/{pg:groupname}/transfer-ownership', [PeergroupController::class, 'editOwner'])->name('editOwner');
Route::put('/{pg:groupname}/transfer-ownership', [PeergroupController::class, 'updateOwner'])->name('updateOwner');

Route::get('/{pg:groupname}/delete', [PeergroupController::class, 'delete'])->name('delete');
Route::delete('/{pg:groupname}/delete', [PeergroupController::class, 'destroy'])->name('destroy');