<?php

namespace Talk\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

    public function edit(Conversation $conversation, Request $request)
    {
        Gate::authorize('edit', $conversation);

        return view('talk::conversations.edit', [
            'conversation' => $conversation,
        ]);
    }

    public function update(Conversation $conversation, Request $request)
    {        
        Gate::authorize('edit', $conversation);
        
        $input = $request->all();

        Validator::make($input, Conversation::getValidationRules()['update'])->validate();

        $conversation->update($input);

        if(key_exists('users', $input)) {
            $users = User::whereIn('username', $input['users'])->get()->all();
            $conversation->syncUsers($users);
        }

        return redirect()->back()->with('success', __('talk::talk.conversation_changed_successfully'));
    }
}