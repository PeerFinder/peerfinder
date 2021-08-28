<?php

namespace App\Models;

use App\Helpers\Facades\Avatar;
use App\Helpers\Facades\Urler;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Matcher\Traits\UserPeergroups;
use Talk\Facades\Talk;
use Talk\Traits\UserConversations;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, UserConversations, UserPeergroups;

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
    ];

    public static function rules() {
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
            # Delete the avatar image when deleting user account
            Avatar::deleteForUser($user);

            Talk::deleteConversationForUser($user);
        });
    }

    public function profileUrl()
    {
        return route('profile.user.show', ['user' => $this->username]);
    }
}
