<?php

use Illuminate\Support\Facades\Route;
use Matcher\Http\Controllers\PeergroupController;

Route::get('/{pg:groupname}', [PeergroupController::class, 'show'])->name('show');
Route::get('/{pg:groupname}/edit', [PeergroupController::class, 'edit'])->name('edit');
Route::put('/{pg:groupname}/edit', [PeergroupController::class, 'update'])->name('update');