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
        return $pg->allowedToJoin($user);
    }

    public function delete(User $user, Membership $membership, Peergroup $pg)
    {
        return $membership->peergroup()->first()->id == $pg->id && $membership->user_id == $user->id;
    }

    public function approve(User $user, Membership $membership, Peergroup $pg)
    {
        return $pg->isOwner($user);
    }

    public function decline(User $user, Membership $membership, Peergroup $pg)
    {
        return $pg->isOwner($user);
    }    
}
