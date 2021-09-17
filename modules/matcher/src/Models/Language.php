<?php

namespace Matcher\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Matcher\Database\Factories\LanguageFactory;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
    ];

    protected static function newFactory()
    {
        return new LanguageFactory();
    }
}
