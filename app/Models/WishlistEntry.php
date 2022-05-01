<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishlistEntry extends Model
{
    use HasFactory;

    public static function rules()
    {
        $updateRules = [
            'body' => ['required', 'string', 'max:1000'],
            'context' => ['nullable', 'string', 'max:500'],
        ];

        return [
            'update' => $updateRules,
            'create' => $updateRules,
        ];
    }    
}
