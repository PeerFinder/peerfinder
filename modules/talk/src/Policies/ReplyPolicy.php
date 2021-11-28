<?php

namespace Talk\Policies;

use Talk\Models\Conversation;
use Talk\Models\Reply;

class ReplyPolicy
{
    public function edit($user, Reply $reply)
    {
        return $reply->user_id == $user->id;
    }
}
