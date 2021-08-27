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

    public static function rules() {
        $updateRules = [
            'code' => ['required', 'regex:/^[a-z]{2}$/', 'max:10'],
            'title' => ['required', 'string', 'max:100'],
        ];

        return [
            'update' => $updateRules,
            'create' => $updateRules,
        ];
    }

    protected static function newFactory()
    {
        return new LanguageFactory();
    }
}
