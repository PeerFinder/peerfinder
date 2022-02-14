<?php

namespace Matcher\Models;

use App\Helpers\Facades\Urler;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Matcher\Facades\Matcher;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Invitation extends Model
{
    use LogsActivity;

    protected $fillable = [
        'peergroup_id',
        'sender_user_id',
        'receiver_user_id',
        'comment',
    ];

    public static function rules()
    {
        $updateRules = [
            'comment' => ['nullable', 'string', 'max:500'],
            'search_users' => 'required',
            'search_users.*' => 'exists:users,username',
        ];

        return [
            'update' => $updateRules,
            'create' => $updateRules,
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invitation) {
            Urler::createUniqueSlug($invitation, 'identifier');
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
