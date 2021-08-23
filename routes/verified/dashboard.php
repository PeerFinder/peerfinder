<?php

use Illuminate\Support\Facades\Route;
use Talk\Facades\Talk;

Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
    Route::get('/', function () {
        $c = \Talk\Models\Conversation::whereId(11)->first();

        #dump($replies[0]->user->username);

        #return "<hr>";

        return view('frontend.dashboard.index', [
            'conversation' => Talk::embedConversation($c),
        ]);
    })->name('index');
});