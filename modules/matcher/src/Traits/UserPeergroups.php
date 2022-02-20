<?php

namespace Matcher\Traits;

use Matcher\Models\Invitation;
use Matcher\Models\Membership;
use Matcher\Models\Peergroup;

trait UserPeergroups
{
    public function peergroups()
    {
        return $this->hasMany(Peergroup::class);
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function ownsPeergroups()
    {
        return $this->peergroups()->count() > 0;
    }

    public function received_invitations()
    {
        return $this->hasMany(Invitation::class, 'receiver_user_id');
    }

    public function sent_invitations()
    {
        return $this->hasMany(Invitation::class, 'sender_user_id');
    }
}
