<?php

namespace GroupRequests\Policies;

use App\Models\User;

class GroupRequestPolicy
{
    public function create(User $user)
    {
        return true;
    }
}
