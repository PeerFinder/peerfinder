<?php

namespace Talk\Models;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Talk\Database\Factories\ReplyFactory;

class Reply extends Model
{
    use HasFactory;

    protected $touches = ['conversation'];

    protected $fillable = [
        'message',
    ];

    public static function rules() {
        $updateRules = [
            'reply' => ['nullable', 'uuid'],
            'message' => ['required_without:reply', 'string', 'max:1000'],
            'reply_message' => ['required_with:reply', 'string', 'max:1000'],
        ];

        return [
            'update' => $updateRules,
            'create' => $updateRules,
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reply) {
            $reply->identifier = (string) Str::uuid();
        });

        static::deleting(function ($reply) {
            $reply->replies()->each(function ($sub_reply) {
                $sub_reply->delete();
            });
        });
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reply()
    {
        return $this->belongsTo(Reply::class, 'reply_id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class, 'reply_id', 'id')->with(['replies']);
    }
}
