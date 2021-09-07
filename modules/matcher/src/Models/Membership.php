<?php

namespace Matcher\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'comment',
        'begin',
    ];

    protected $casts = [
        'begin' => 'date',
        'approved' => 'boolean',
    ];

    public function peergroup()
    {
        return $this->belongsTo(Peergroup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
