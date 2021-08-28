<?php

namespace Matcher\Traits;

use Matcher\Models\Peergroup;

trait UserPeergroups
{
    public function peergroups()
    {
        return $this->hasMany(Peergroup::class);
    }
}