<?php

use Illuminate\Support\Facades\Route;
use Talk\Http\Controllers\ConversationController;

Route::get('/', [ConversationController::class, 'index'])->name('index');
Route::get('/dm/{user:username}', [ConversationController::class, 'directMessage'])->name('direct');
Route::get('/{conversation:identifier}', [ConversationController::class, 'show'])->name('show');
Route::get('/{conversation:identifier}/edit', [ConversationController::class, 'edit'])->name('edit');
Route::put('/{conversation:identifier}/update', [ConversationController::class, 'update'])->name('update');
