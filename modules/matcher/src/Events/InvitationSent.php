<?php

namespace Matcher\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Matcher\Models\Invitation;
use Matcher\Models\Membership;
use Matcher\Models\Peergroup;

class InvitationSent
{
    use Dispatchable, SerializesModels;

    public $pg;
    public $sender;
    public $receiver;
    public $invitation;

    public function __construct(Peergroup $pg, User $receiver, User $sender, Invitation $invitation)
    {
        $this->pg = $pg;
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->invitation = $invitation;
    }
}