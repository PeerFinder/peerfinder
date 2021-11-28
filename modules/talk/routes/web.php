<?php

use Illuminate\Support\Facades\Route;
use Talk\Http\Controllers\ConversationController;
use Talk\Http\Controllers\RepliesController;

Route::get('/', [ConversationController::class, 'index'])->name('index');
Route::get('/create/{user:username}', [ConversationController::class, 'createForUser'])->name('create.user');
Route::put('/create/{user:username}', [ConversationController::class, 'storeForUser'])->name('store.user');
#Route::get('/create', [ConversationController::class, 'create'])->name('create');
#Route::put('/create', [ConversationController::class, 'store'])->name('store');
Route::get('/{conversation:identifier}', [ConversationController::class, 'show'])->name('show');
Route::get('/{conversation:identifier}/edit', [ConversationController::class, 'edit'])->name('edit');
Route::put('/{conversation:identifier}/update', [ConversationController::class, 'update'])->name('update');

Route::put('/{conversation:identifier}/replies/create', [RepliesController::class, 'store'])->name('replies.store');