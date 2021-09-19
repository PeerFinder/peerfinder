<?php

namespace Matcher\Traits;

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
}