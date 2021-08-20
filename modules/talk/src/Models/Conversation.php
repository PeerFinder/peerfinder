<?php

namespace Talk\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Talk\Database\Factories\ConversationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public static function getValidationRules() {
        $updateRules = [
            'title' => ['nullable', 'string', 'max:255'],
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
        return $this->users()->syncWithoutDetaching([$user->id]);
    }

    public function syncUsers($users)
    {
        $user_ids = array_map(fn($user) => $user->id, $users);
        return $this->users()->sync($user_ids);
    }

    public function setOwner($owner)
    {
        $this->conversationable()->associate($owner);
        return $this->save();
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

    public static function forUsers($users)
    {
        $query = Conversation::with('users');

        // Check for participation
        foreach ($users as $user) {
            $query->whereHas('users', function (Builder $sub_query) use ($user) {
                $sub_query->where('user_id', $user->id);
            });
        }

        // Check for ownership
        $query->whereHasMorph('conversationable', [User::class], function(Builder $query) use ($users) {
            $query->whereIn('conversationable_id', array_map(fn($user) => $user->id, $users));
        });

        return $query;
    }
}
