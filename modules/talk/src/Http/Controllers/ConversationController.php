<?php

namespace Talk\Http\Controllers;

use App\Helpers\Facades\Urler;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Talk\Facades\Talk;
use Talk\Models\Conversation;
use Talk\Models\Receipt;
use Talk\Models\Reply;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        return view('talk::conversations.index');
    }

    public function createForUser(User $user, Request $request)
    {
        $ret = Talk::checkConversationCreation($user);

        if ($ret) {
            return $ret;
        }

        $conversation = new Conversation();
        $conversation->title = __('talk::talk.start_conversation_with', ['participants' => Talk::usersAsString(Talk::filterUsers([$user]))]);

        return view('talk::conversations.create', [
            'participants' => [$user],
            'conversation' => $conversation,
        ]);
    }

    public function storeForUser(User $user, Request $request)
    {
        $ret = Talk::checkConversationCreation($user);
        
        if ($ret) {
            return $ret;
        }
    
        Talk::createConversation(auth()->user(), [auth()->user(), $user], $request->all());

        return redirect()->back()->with('success', __('talk::talk.conversation_created_successfully'));
    }

    public function show(Request $request, Conversation $conversation)
    {
        Gate::authorize('view', $conversation);

        $unread = false;

        $unread = $conversation->markAsRead();

        $last_reply = $conversation->replies()->orderByDesc('created_at')->first('identifier');

        $view = view('talk::conversations.show', [
            'conversation' => $conversation,
            'replies' => Talk::repliesTree($conversation),
            'unread' => $unread,
            'highlighted_reply' => $last_reply ? $last_reply->identifier : null,
        ]);

        return $view;
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

        Validator::make($input, Conversation::rules()['update'])->validate();

        $conversation->update($input);

        if(key_exists('users', $input)) {
            $users = User::whereIn('username', $input['users'])->get()->all();
            $conversation->syncUsers($users);
        }

        return redirect($conversation->getUrl())->with('success', __('talk::talk.conversation_changed_successfully'));
    }
}
