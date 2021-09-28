<?php

namespace Matcher\Policies;

use App\Models\User;
use Matcher\Models\Peergroup;

class PeergroupPolicy
{
    public function create(User $user)
    {
        return true;
    }

    public function view(User $user, Peergroup $pg)
    {
        if (!$pg->private) {
            return true;
        }

        if ($pg->isOwner($user)) {
            return true;
        }

        if ($pg->isMember($user)) {
            return true;
        }

        return false;
    }

    public function forMembers(User $user, Peergroup $pg)
    {
        if ($pg->isOwner($user)) {
            return true;
        }

        if ($pg->isMember($user)) {
            return true;
        }

        return false;
    }    

    public function edit(User $user, Peergroup $pg)
    {
        return $pg->isOwner($user);
    }

    public function complete(User $user, Peergroup $pg)
    {
        return $pg->isOwner($user);
    }

    public function editOwner(User $user, Peergroup $pg)
    {
        return $pg->isOwner($user);
    }

    public function delete(User $user, Peergroup $pg)
    {
        return $pg->isOwner($user);
    }    
}
