<?php

namespace Talk\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}