<?php

namespace Matcher\Policies;

use Matcher\Models\Peergroup;

class PeergroupPolicy
{
    public function view($user, Peergroup $pg)
    {
        if (!$pg->private) {
            return true;
        }

        return false;
    }

    public function edit($user, Peergroup $pg)
    {
        return true;
    }
}
