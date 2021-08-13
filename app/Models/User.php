<?php

namespace App\Models;

use App\Helpers\Facades\Avatar;
use App\Helpers\Facades\Urler;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            Urler::createUniqueSlug($user, 'username');
        });

        static::deleting(function ($user) {
            # Delete the avatar image when deleting user account
            Avatar::deleteForUser($user);
        });
    }
}
