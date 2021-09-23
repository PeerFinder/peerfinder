<?php

namespace Matcher\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Matcher\Facades\Matcher;

class Bookmark extends Model
{
    protected $fillable = [
        'url',
        'title',
    ];

    public static function rules() {
        $updateRules = [
            'url' => ['string', 'url', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
        ];

        return [
            'update' => $updateRules,
            'create' => $updateRules,
        ];
    }

    public function peergroup()
    {
        return $this->belongsTo(Peergroup::class);
    }
}
