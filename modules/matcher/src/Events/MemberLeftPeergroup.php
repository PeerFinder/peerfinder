<?php

namespace Matcher\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Matcher\Models\Peergroup;

class MemberLeftPeergroup
{
    use Dispatchable, SerializesModels;

    public $pg;
    public $user;

    public function __construct(Peergroup $pg, User $user)
    {
        $this->pg = $pg;
        $this->user = $user;
    }
}