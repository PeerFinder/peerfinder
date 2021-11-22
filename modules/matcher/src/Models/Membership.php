<?php

namespace Matcher\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Matcher\Facades\Matcher;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Membership extends Model
{
    use LogsActivity;

    public const ROLE_MEMBER = 0;
    public const ROLE_CO_OWNER = 1;

    protected $fillable = [
        'comment',
        'begin',
    ];

    protected $casts = [
        'begin' => 'date',
        'approved' => 'boolean',
    ];

    protected $touches = ['peergroup'];

    public static function rules()
    {
        $updateRules = [
            'comment' => ['nullable', 'string', 'max:500']
        ];

        return [
            'update' => $updateRules,
            'create' => $updateRules,
        ];
    }

    public static function memberRoles()
    {
        return [
            static::ROLE_MEMBER => __('matcher::peergroup.role_member'),
            static::ROLE_CO_OWNER => __('matcher::peergroup.role_co_owner'),
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($membership) {
            # Update the states of the group, like if it is full/closed or not
            $membership->peergroup()->first()->updateStates();

            # Status of approved changed from false to true
            if ($membership->wasChanged('approved') && $membership->approved) {
                Matcher::afterUserApproved($membership->peergroup()->first(), $membership->user()->first(), $membership);
                Matcher::afterMemberAdded($membership->peergroup()->first(), $membership->user()->first(), $membership);
            }
        });

        static::created(function ($membership) {
            if ($membership->user) {
                if ($membership->approved) {
                    Matcher::afterMemberAdded($membership->peergroup, $membership->user, $membership);
                } else {
                    Matcher::afterUserRequestedToJoin($membership->peergroup, $membership->user, $membership);
                }
            }
        });

        static::deleting(function ($membership) {
            if ($membership->approved && $membership->user) {
                Matcher::beforeMemberRemoved($membership->peergroup, $membership->user);
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

    public function memberRole()
    {
        return $this->memberRoles()[$this->member_role_id];
    }
}
