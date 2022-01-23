<?php

use Illuminate\Support\Facades\Route;
use Talk\Http\Controllers\ConversationController;
use Talk\Http\Controllers\RepliesController;

Route::get('/', [ConversationController::class, 'index'])->name('index');
Route::get('/create/{usernames}', [ConversationController::class, 'createForUser'])->name('create.user');
Route::put('/create/{usernames}', [ConversationController::class, 'storeForUser'])->name('store.user');

Route::get('/select', [ConversationController::class, 'select'])->name('select');
Route::post('/select', [ConversationController::class, 'selectAndRedirect'])->name('selectAndRedirect');

Route::get('/{conversation:identifier}', [ConversationController::class, 'show'])->name('show');
Route::get('/{conversation:identifier}/edit', [ConversationController::class, 'edit'])->name('edit');
Route::put('/{conversation:identifier}/update', [ConversationController::class, 'update'])->name('update');

Route::put('/{conversation:identifier}/replies/create', [RepliesController::class, 'store'])->name('replies.store');
Route::get('/{conversation:identifier}/replies/{reply:identifier}/show', [RepliesController::class, 'show'])->name('replies.show');
Route::put('/{conversation:identifier}/replies/{reply:identifier}/update', [RepliesController::class, 'update'])->name('replies.update');