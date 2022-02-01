<?php

namespace Talk\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Talk\Models\Receipt;

class UnreadReply
{
    use Dispatchable, SerializesModels;

    public $receipt;

    public function __construct(Receipt $receipt)
    {
        $this->receipt = $receipt;
    }
}