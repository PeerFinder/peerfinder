<?php

namespace Matcher\Policies;

use App\Models\User;
use Matcher\Models\Invitation;
use Matcher\Models\Membership;
use Matcher\Models\Peergroup;

class InvitationPolicy
{
    public function create(User $user, Peergroup $pg)
    {
        if ($pg->isOwner($user)) {
            return true;
        }

        if ($pg->isMember($user)) {
            return true;
        }

        return false;
    }
}