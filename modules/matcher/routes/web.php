<?php

use Illuminate\Support\Facades\Route;
use Matcher\Http\Controllers\PeergroupController;

Route::get('/{pg:groupname}', [PeergroupController::class, 'show'])->name('show');