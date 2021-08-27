<?php

namespace Matcher\Models;

use App\Helpers\Facades\Urler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Matcher\Database\Factories\PeergroupFactory;

class Peergroup extends Model
{
    use HasFactory;

    protected $casts = [
        'begin' => 'date',
    ];

    protected $fillable = [
        'title',
        'description',
        'limit',
        'begin',
        'virtual',
        'private',
        'open',
        'with_approval',
        'location',
        'meeting_link',
    ];

    public static function rules() {
        $updateRules = [
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:800'],
            'limit' => ['required', 'integer', 'min:2', 'max:' . config('matcher.max_limit')],
            'begin' => ['required', 'date'],
            'virtual' => ['required', 'boolean'],
            'private' => ['required', 'boolean'],
            'with_approval' => ['required', 'boolean'],
            'location' => ['nullable', 'string'],
            'meeting_link' => ['nullable', 'string', new \App\Rules\UrlerValidUrl()],
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
            Urler::createUniqueSlug($user, 'groupname');
        });

        static::deleting(function ($user) {

        });
    }    

    protected static function newFactory()
    {
        return new PeergroupFactory();
    }


}
