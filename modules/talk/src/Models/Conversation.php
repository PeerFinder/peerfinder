<?php

namespace Talk\Models;

use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Talk\Database\Factories\ConversationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Talk\Facades\Talk;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public static function rules() {
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

            $conversation->receipts()->each(function ($receipt) {
                $receipt->delete();
            });

            $conversation->replies()->each(function ($reply) {
                $reply->delete();
            });
        });
    }

    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class)->withTimestamps();
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
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

    /**
     * Searches for the conversation with an exact list of users. At least one of those
     * user has to be also the owner of the conversation (otherwise the owner can leave
     * the conversation and other participants cannot create another one with same users).
     * 
     * @param mixed $users List of participants
     * @return Builder query
     */
    public static function forUsers($users)
    {
        $query = Conversation::withCount('users')->having('users_count', '=', count($users));

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

    public function getReplies()
    {
        return Reply::where('conversation_id', $this->id)
                    ->with('user')
                    ->paginate(config('talk.replies_per_page', 20));
    }

    public function isUnread()
    {
        return ($this->receipts->count() > 0);
    }

    /**
     * Marks the conversation as read for the current user by
     * deleting the corresponding receipt from the database.
     * 
     * @return bool previous read/unread state
     */
    public function markAsRead()
    {
        $receipt = Receipt::where('conversation_id', $this->id)->where('user_id', auth()->user()->id);
        $was_unread = $receipt->exists();
        $receipt->delete();
        return $was_unread;
    }

    /**
     * Returns the URL of current conversation. If the reply is provided,
     * the reply identifier is attached as hash-parameter to the URL.
     * 
     * @param mixed|null $reply Reply Object
     * @return string URL
     */
    public function getUrl($reply = null)
    {
        if ($reply) {
            return route('talk.show', ['conversation' => $this->identifier, '#reply-' . $reply->identifier]);
        } else {
            return route('talk.show', ['conversation' => $this->identifier]);
        }
    }

    /**
     * Returns the title stored in the database. If the title is empty, returns
     * the list of participants
     * 
     * @return string 
     */
    public function getTitle()
    {
        if ($this->title) {
            return $this->title;
        } else {
            return Talk::usersAsString(Talk::filterUsersForConversation($this));
        }
    }
}
