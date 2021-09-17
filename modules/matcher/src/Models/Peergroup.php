<?php

namespace Matcher\Models;

use App\Helpers\Facades\Urler;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Matcher\Database\Factories\PeergroupFactory;
use Matcher\Facades\Matcher;

class Peergroup extends Model
{
    use HasFactory;

    protected $casts = [
        'begin' => 'date',
        'limit' => 'int',
        'open' => 'boolean',
        'virtual' => 'boolean',
        'private' => 'boolean',
        'with_approval' => 'boolean',
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
            'languages' => ['required', 'exists:languages,code'],
        ];

        return [
            'update' => $updateRules,
            'create' => $updateRules,
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pg) {
            Urler::createUniqueSlug($pg, 'groupname');
        });

        static::created(function ($pg) {
            Matcher::afterPeergroupCreated($pg);
        });

        static::saving(function ($pg) {
            # If the group is full, mark is also as closed
            if ($pg->isFull()) {
                $pg->open = false;
            }
        });

        static::deleting(function ($pg) {
            $pg->languages()->detach();

            $pg->memberships()->each(function ($membership) {
                $membership->delete();
            });

            Matcher::beforePeergroupDeleted($pg);
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

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function members()
    {
        return $this->hasManyThrough(User::class, Membership::class, 'peergroup_id', 'id', 'id', 'user_id')->where('approved', true);
    }

    public function getMembers()
    {
        if ($this->relationLoaded('memberships')) {
            return $this->members;
        } else {
            return $this->members()->get();
        }
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class)->withTimestamps();
    }

    public function isOwner(User $user)
    {
        return $this->user->id == $user->id;
    }

    public function setOwner(User $user)
    {
        $this->user_id = $user->id;
        $this->save();
        #TODO: Notify the new owner about the group
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

    public function allowedToJoin(User $user = null)
    {
        $user = $user ?: auth()->user();

        if ($this->isOwner($user)) {
            return true;
        }

        if ($this->private) {
            return false;
        }

        return true;
    }

    public function isMember(User $user = null, $include_not_approved = false)
    {
        $user = $user ?: auth()->user();

        $query = Membership::where(['user_id' => $user->id, 'peergroup_id' => $this->id]);

        if (!$include_not_approved) {
            $query = $query->where('approved', true);
        }

        return $query->exists();
    }

    public function isPending(User $user = null)
    {
        $user = $user ?: auth()->user();

        $query = Membership::where(['user_id' => $user->id, 'peergroup_id' => $this->id, 'approved' => false]);

        return $query->exists();
    }    

    /**
     * Check if there are more members in this group other than the owner
     * @return true/false 
     */
    public function hasMoreMembersThanOwner()
    {
        $members = $this->getMembers();

        $owner = $this->user()->first();

        $filtered = $members->reject(function ($value, $key) use ($owner) {
            return $value->id == $owner->id;
        });

        return $filtered->count() > 0;
    }

    public function isFull()
    {
        return $this->getMembers()->count() >= $this->limit;
    }

    public function isOpen()
    {
        return $this->open;
    }

    public function complete()
    {
        $this->open = false;
        $this->save();
    }

    public function uncomplete()
    {
        $this->open = true;
        $this->save();
    }

    public function canUncomplete()
    {
        return !($this->isOpen() || $this->isFull());
    }

    public function updateStates()
    {
        $this->refresh();

        if ($this->isFull()) {
            $this->complete();
        }
    }
}
