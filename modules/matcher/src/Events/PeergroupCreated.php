<?php

namespace Matcher\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Matcher\Models\Peergroup;

class PeergroupCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pg;

    public function __construct(Peergroup $pg)
    {
        $this->pg = $pg;
    }
}