<?php

namespace Matcher\Policies;

use App\Models\User;
use Matcher\Models\Membership;
use Matcher\Models\Peergroup;

class PeergroupPolicy
{
    public function create(User $user)
    {
        return true;
    }

    /**
     * Displaying the peergroup
     * Allowed to:
     *  + everyone if the group is not private
     *  + owner
     *  + member of the group
     */
    public function view(User $user, Peergroup $pg)
    {
        if (!$pg->private || $pg->userHasInvitation($user)) {
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

    /**
     * Displaying details
     * Allowed to:
     *  + owner
     *  + member of the group
     */
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

    /**
     * Editing the peergroup
     * Allowed to:
     *  + owner
     *  + co-owner
     */
    public function edit(User $user, Peergroup $pg)
    {
        return $pg->isOwner($user) || $pg->memberHasRole($user, Membership::ROLE_CO_OWNER);
    }

    /**
     * Completing the peergroup
     * Allowed to:
     *  + owner
     *  + co-owner
     */
    public function complete(User $user, Peergroup $pg)
    {
        return $pg->isOwner($user) || $pg->memberHasRole($user, Membership::ROLE_CO_OWNER);
    }

    /**
     * Editing peergroup's owner
     * Allowed to:
     *  + owner
     */
    public function editOwner(User $user, Peergroup $pg)
    {
        return $pg->isOwner($user);
    }

    /**
     * Deleting peergroup
     * Allowed to:
     *  + owner
     */
    public function delete(User $user, Peergroup $pg)
    {
        return $pg->isOwner($user);
    }

    /**
     * Managing members
     * Allowed to:
     *  + owner
     *  + co-owner
     */
    public function manageMembers(User $user, Peergroup $pg)
    {
        return $pg->isOwner($user) || $pg->memberHasRole($user, Membership::ROLE_CO_OWNER);
    }
}
