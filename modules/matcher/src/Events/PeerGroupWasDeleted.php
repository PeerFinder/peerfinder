<?php

namespace Matcher\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Matcher\Models\Peergroup;

class PeergroupWasDeleted
{
    use Dispatchable, SerializesModels;

    public $pg;

    public function __construct(Peergroup $pg)
    {
        $this->pg = $pg;
    }
}