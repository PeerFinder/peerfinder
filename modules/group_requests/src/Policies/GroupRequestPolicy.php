<?php

namespace GroupRequests\Policies;

use App\Models\User;
use GroupRequests\Models\GroupRequest;

class GroupRequestPolicy
{
    public function create(User $user)
    {
        return true;
    }

    public function edit(User $user, GroupRequest $group_request)
    {
        return $group_request->user_id == $user->id;
    }

    public function delete(User $user, GroupRequest $group_request)
    {
        return $group_request->user_id == $user->id;
    }
}
