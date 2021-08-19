<?php

namespace Talk\Models;

use App\Models\User;
use Talk\Database\Factories\ConversationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
    ];

    public static function getValidationRules() {
        $updateRules = [
            'title' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:255'],
        ];

        return [
            'update' => $updateRules,
            'create' => $updateRules,
        ];
    }
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($conversation) {
            $conversation->identifier = (string) Str::uuid();
        });

        static::deleting(function ($conversation) {
            $conversation->users()->detach();
        });
    }

    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class)->withTimestamps();
    }

    protected static function newFactory()
    {
        return new ConversationFactory();
    }

    public function conversationable()
    {
        return $this->morphTo();
    }

    public function addUser(User $user)
    {
        $this->users()->syncWithoutDetaching([$user->id]);
    }

    public function setOwner($owner)
    {
        $this->conversationable()->associate($owner);
        $this->save();
    }

    public function isOwner($owner)
    {
        if (get_class($this->conversationable) === get_class($owner)) {
            return $this->conversationable->id == $owner->id;
        } else {
            return false;
        }
    }

    public function isParticipant(User $user)
    {
        return $this->users->contains($user);
    }
}