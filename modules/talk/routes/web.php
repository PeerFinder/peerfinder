<?php

use Illuminate\Support\Facades\Route;
use Talk\Http\Controllers\ConversationController;

Route::get('/', [ConversationController::class, 'index'])->name('index');
Route::get('/{conversation:identifier}', [ConversationController::class, 'show'])->name('show');