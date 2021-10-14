<?php

namespace Matcher\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Matcher\Models\Membership;
use Matcher\Models\Peergroup;

class UserRequestedToJoin
{
    use Dispatchable, SerializesModels;

    public $pg;
    public $user;
    public $membership;

    public function __construct(Peergroup $pg, User $user, Membership $membership)
    {
        $this->pg = $pg;
        $this->user = $user;
        $this->membership = $membership;
    }
}