<?php

use Illuminate\Support\Facades\Route;
use Matcher\Http\Controllers\PeergroupController;

Route::get('/{pg:groupname}', [PeergroupController::class, 'show'])->name('show');
Route::get('/{pg:groupname}/edit', [PeergroupController::class, 'edit'])->name('edit');
Route::put('/{pg:groupname}/edit', [PeergroupController::class, 'update'])->name('update');

Route::get('/{pg:groupname}/complete', [PeergroupController::class, 'editCompleted'])->name('editCompleted');
Route::put('/{pg:groupname}/complete', [PeergroupController::class, 'updateCompleted'])->name('updateCompleted');

Route::get('/{pg:groupname}/owner', [PeergroupController::class, 'editOwner'])->name('editOwner');
Route::put('/{pg:groupname}/owner', [PeergroupController::class, 'updateOwner'])->name('updateOwner');

Route::get('/{pg:groupname}/delete', [PeergroupController::class, 'delete'])->name('delete');
Route::delete('/{pg:groupname}/delete', [PeergroupController::class, 'destroy'])->name('destroy');