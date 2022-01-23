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
    
    public function select(Request $request)
    {
        $conversation = new Conversation();
        $users_and_errors = [];

        if ($request->old('_token') && $request->old('users')) {
            $users = $request->old('users');
            $errors = session()->get('errors')->getBag('default');
            
            for ($i=0; $i < count($users); $i++) { 
                $username = $users[$i];
                $name = User::whereUsername($username)->pluck('name')->first() ?: $username;
                $error = $errors->get('users.' . $i);

                $users_and_errors[] = [
                    'id' => $username,
                    'value' => $name,
                    'error' => $error && count($error),
                ];
            }
        }

        return view('talk::conversations.select', [
            'conversation' => $conversation,
            'users' => collect($users_and_errors),
        ]);
    }

    public function selectAndRedirect(Request $request)
    {
        $input = $request->all();

        Validator::make($input, [
            'users' => 'required',
            'users.*' => 'exists:users,username'
        ])->validate();

        return redirect(route('talk.create.user', ['usernames' => implode(',', $input['users'])]));
    }

    private function checkAndGetUsers($usernamesString)
    {
        $usernames = array_unique(explode(',', $usernamesString));

        $users = User::whereIn('username', $usernames)->get();

        if ($users->count() != count($usernames)) {
            abort(404);
        }

        return Talk::filterUsers($users->all(), 0);
    }

    public function createForUser(Request $request, $usernamesString)
    {
        $users = $this->checkAndGetUsers($usernamesString);

        $ret = Talk::checkConversationCreation($users);

        if ($ret) {
            return $ret;
        }

        $conversation = new Conversation();
        $conversation->title = __('talk::talk.start_conversation_with', ['participants' => Talk::usersAsString(Talk::filterUsers($users))]);

        $participantsString = implode(',', array_map(fn($u) => $u->username, $users));

        return view('talk::conversations.create', [
            'participants' => $users,
            'participantsString' => $participantsString,
            'conversation' => $conversation,
        ]);
    }

    public function storeForUser(Request $request, $usernamesString)
    {
        $users = $this->checkAndGetUsers($usernamesString);

        $ret = Talk::checkConversationCreation($users);
        
        if ($ret) {
            return $ret;
        }

        $users[] = auth()->user();

        Talk::createConversation(auth()->user(), $users, $request->all());

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
