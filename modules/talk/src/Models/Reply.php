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

    protected $fillable = [
        'message',
    ];

    public static function getValidationRules() {
        $updateRules = [
            'message' => ['required', 'string', 'max:1000'],
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

        static::deleting(function ($conversation) {
            
        });
    }

    protected static function newFactory()
    {
        return new ReplyFactory();
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Reply::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }
}
