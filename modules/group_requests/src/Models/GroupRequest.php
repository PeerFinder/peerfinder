<?php

namespace GroupRequests\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use GroupRequests\Database\Factories\GroupRequestFactory;
use GroupRequests\Facades\GroupRequests;
use Matcher\Models\Language;
use Talk\Models\Conversation;
use Talk\Traits\GroupRequestConversations;

class GroupRequest extends Model
{
    use HasFactory, GroupRequestConversations;

    protected $fillable = [
        'description',
        'title',
    ];

    public static function rules()
    {
        $updateRules = [
            'title' => ['string', 'max:255'],
            'description' => ['string', 'max:500'],
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

        static::creating(function ($group_request) {
            $group_request->identifier = (string) Str::uuid();
        });

        static::deleting(function ($group_request) {
            $group_request->languages()->detach();

            $group_request->conversations()->each(function (Conversation $conversation) {
                $conversation->delete();
            });
        });

        static::created(function ($group_request) {
            GroupRequests::createConversationForGroupRequest($group_request);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class)->withTimestamps();
    }

    protected static function newFactory()
    {
        return new GroupRequestFactory();
    }

    public function getUrl()
    {
        return route('group_requests.show', ['group_request' => $this->identifier]);
    }
}
