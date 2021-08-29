<?php

namespace Matcher\Models;

use App\Helpers\Facades\Urler;
use App\Models\User;
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
            'location' => ['nullable', 'string', 'max:100'],
            'meeting_link' => ['nullable', 'string', 'max:255', new \App\Rules\UrlerValidUrl()],
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class)->withTimestamps();
    }

    public function isOwner(User $user)
    {
        return $this->user->id == $user->id;
    }

    public function getUrl()
    {
        return route('matcher.show', ['pg' => $this->groupname]);
    }

    public function needsApproval(User $user = null)
    {
        $user = $user ?: auth()->user();

        if ($this->isOwner($user)) {
            return false;
        }

        if ($this->with_approval) {
            return true;
        }

        return false;
    }

    public function isMember(User $user = null)
    {
        $user = $user ?: auth()->user();

        return false;
    }
}
