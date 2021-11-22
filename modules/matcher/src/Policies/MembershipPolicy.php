<?php

namespace Matcher\Policies;

use App\Models\User;
use Matcher\Facades\Matcher;
use Matcher\Models\Peergroup;
use Matcher\Models\Membership;

class MembershipPolicy
{
    /**
     * Permission for the user itself, if she/he is allowed to join this group
     */
    public function create(User $user, Peergroup $pg)
    {
        return $pg->allowedToJoin($user);
    }

    /**
     * Editing the membership
     * Allowed to: 
     *  + member of the group
     */
    public function edit(User $user, Membership $membership, Peergroup $pg)
    {
        return ($membership->peergroup()->first()->id == $pg->id) && ($membership->user_id == $user->id);
    }    

    /**
     * Deleting the membership (= leaving the group)
     * Allowed to: 
     *  + member of the group
     */
    public function delete(User $user, Membership $membership, Peergroup $pg)
    {
        return ($membership->peergroup()->first()->id) == ($pg->id && $membership->user_id == $user->id);
    }

    /**
     * Approving the membership
     * Allowed to: 
     *  + owner of the group
     */
    public function approve(User $user, Membership $membership, Peergroup $pg)
    {
        return $pg->isOwner($user);
    }

    /**
     * Declining the membership
     * Allowed to:
     *  + owner of the group
     */
    public function decline(User $user, Membership $membership, Peergroup $pg)
    {
        return $pg->isOwner($user);
    }
}
