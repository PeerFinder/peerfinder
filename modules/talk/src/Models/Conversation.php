<?php

namespace Talk\Models;

use Talk\Database\Factories\ConversationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Conversation extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($conversation) {
            $conversation->identifier = (string) Str::uuid();
        });

        /*
        static::deleting(function ($conversation) {

        });
        */
    }

    protected static function newFactory()
    {
        return new ConversationFactory();
    }
}