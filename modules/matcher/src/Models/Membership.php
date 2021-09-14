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

    public static function rules() {
        $updateRules = [
            'comment' => ['nullable', 'string', 'max:500']
        ];

        return [
            'update' => $updateRules,
            'create' => $updateRules,
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($membership) {
            $membership->peergroup()->first()->updateStates();
        });
    }

    public function peergroup()
    {
        return $this->belongsTo(Peergroup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approve()
    {
        $this->approved = true;
        $this->save();
    }
}
