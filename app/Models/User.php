<?php

namespace App\Models;

use App\Helpers\Facades\Avatar;
use App\Helpers\Facades\Urler;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Matcher\Facades\Matcher;
use Matcher\Traits\UserPeergroups;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Talk\Facades\Talk;
use Talk\Traits\UserConversations;

class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference
{
    use HasFactory, 
        Notifiable,
        UserConversations,
        UserPeergroups,
        LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'timezone',
        'password',
        'slogan',
        'about',
        'homepage',
        'company',
        'facebook_profile',
        'twitter_profile',
        'linkedin_profile',
        'xing_profile',
        'locale',
    ];

    protected $attributes = [
        'timezone' => 'UTC',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_seen' => 'datetime',
        'is_supporter' => 'bool',
        'is_verified_person' => 'bool',
    ];

    public static function rules()
    {
        $updateRules = [
            'name' => ['required', 'string', 'max:255'],
            'slogan' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string', 'max:1000'],
            'homepage' => ['nullable', 'max:255', new \App\Rules\UrlerValidUrl()],
            'company' => ['nullable', 'string', 'max:255'],
            'facebook_profile' => ['nullable', new \App\Rules\UrlerValidUrl(), 'max:255'],
            'linkedin_profile' => ['nullable', new \App\Rules\UrlerValidUrl(), 'max:255'],
            'twitter_profile' => ['nullable', new \App\Rules\UrlerValidUrl(), 'max:255'],
            'xing_profile' => ['nullable', new \App\Rules\UrlerValidUrl(), 'max:255'],
        ];

        return [
            'update' => $updateRules,
            'create' => $updateRules,
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            Urler::createUniqueSlug($user, 'username');
        });

        static::deleting(function ($user) {
            Avatar::deleteForUser($user);
            Talk::cleanupForUser($user);
            Matcher::cleanupForUser($user);
        });
    }

    public function profileUrl()
    {
        return route('profile.user.show', ['user' => $this->username]);
    }

    public function preferredLocale()
    {
        return $this->locale;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logExcept(['password']);
    }
}
