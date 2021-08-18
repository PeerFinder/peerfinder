<?php

namespace Talk\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Talk\Models\Conversation;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        return view('talk::conversations.index');
    }

    public function show(Request $request, Conversation $conversation)
    {
        
        return view('talk::conversations.show', [
            'conversation' => $conversation,
        ]);
    }    
}