<?php

namespace Talk\Http\Controllers;

use Illuminate\Support\Facades\Gate;
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
        Gate::authorize('view', $conversation);

        return view('talk::conversations.show', [
            'conversation' => $conversation,
        ]);
    }

    public function edit(Request $request, Conversation $conversation)
    {
        Gate::authorize('edit', $conversation);

        return view('talk::conversations.edit', [
            'conversation' => $conversation,
        ]);
    }

    public function update(Request $request, Conversation $conversation)
    {
        Gate::authorize('edit', $conversation);

        
    }    
}