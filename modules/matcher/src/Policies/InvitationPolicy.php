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
        if ($pg->isOwner($user) || $pg->memberHasRole($user, Membership::ROLE_CO_OWNER)) {
            return true;
        }

        if ($pg->isMember($user) && !$pg->restrict_invitations) {
            return true;
        }

        return false;
    }
}