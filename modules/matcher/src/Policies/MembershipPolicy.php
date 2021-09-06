<?php

namespace Matcher\Policies;

use App\Models\User;
use Matcher\Facades\Matcher;
use Matcher\Models\Peergroup;
use Matcher\Models\Membership;

class MembershipPolicy
{
    public function create(User $user, Peergroup $pg)
    {
        return Matcher::canUserJoinGroup($pg, $user, false);
    }

    public function edit(User $user, Peergroup $pg, Membership $membership)
    {
        return false;
    }

    public function delete(User $user, Peergroup $pg, Membership $membership)
    {
        return false;
    }    
}
