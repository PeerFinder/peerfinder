<?php

namespace Talk\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Talk\Facades\Talk;
use Talk\Models\Conversation;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        return view('talk::conversations.index');
    }

    private function checkConversationCreation($user)
    {
        if ($user->id == auth()->user()->id) {
            return redirect(route('talk.index'));
        }
        
        $conversation = Conversation::forUsers([auth()->user(), $user])->get()->first();

        if ($conversation) {
            return redirect(route('talk.show', ['conversation' => $conversation->identifier]));
        }

        return null;
    }

    public function createForUser(User $user, Request $request)
    {
        $ret = $this->checkConversationCreation($user);

        if ($ret) {
            return $ret;
        }

        return view('talk::conversations.create', [
            'participants' => [$user],
        ]);
    }

    public function storeForUser(User $user, Request $request)
    {
        $ret = $this->checkConversationCreation($user);
        
        if ($ret) {
            return $ret;
        }
    
        $conversation = Talk::createConversation(auth()->user(), [auth()->user(), $user], $request->all());

        #TODO: Save the message here

        return redirect()->back()->with('success', __('talk::talk.conversation_create_successfully'));
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
