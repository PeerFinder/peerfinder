<?php

namespace Talk\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }    
}