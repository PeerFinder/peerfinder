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

class RepliesController extends Controller
{
    public function store(Conversation $conversation, Request $request)
    {
        Gate::authorize('view', $conversation);

        $input = $request->input();

        Talk::createReply($conversation, auth()->user(), $input);

        return redirect()->back()->with('success', __('talk::talk.reply_posted_successfully'));
    }

    public function show(Conversation $conversation, Reply $reply, Request $request)
    {
        Gate::authorize('view', [$reply, $conversation]);

        if ($request->get('raw', 'true') == 'true') {
            return response()->json(['message' => $reply->message]);
        } else {
            return response()->json(['message' => Talk::renderReplyMessage($reply->message)]);
        }
    }

    public function update(Conversation $conversation, Reply $reply, Request $request)
    {
        Gate::authorize('edit', [$reply, $conversation]);

        $input = $request->all();

        $validator = Validator::make($input, Reply::rules()['create']);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $conversation->touch();

        $reply->message = $input['message'];
        $reply->save();
    }
}
