<?php

namespace Talk\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = [
        'user_id',
        'conversation_id',
    ];
}