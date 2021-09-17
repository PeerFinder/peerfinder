<?php

namespace Matcher\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Matcher\Facades\Matcher;

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

            if ($membership->wasChanged('approved') && $membership->approved) {
                Matcher::afterMemberAdded($membership->peergroup()->first(), $membership->user()->first(), $membership);
            }
        });

        static::created(function ($membership) {
            if ($membership->approved) {
                Matcher::afterMemberAdded($membership->peergroup()->first(), $membership->user()->first(), $membership);
            }
        });

        static::deleting(function ($membership) {
            if ($membership->approved) {
                Matcher::beforeMemberRemoved($membership->peergroup()->first(), $membership->user()->first());
            }
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
