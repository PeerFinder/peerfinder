<?php

namespace Matcher\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Matcher\Database\Factories\BookmarkFactory;
use Matcher\Facades\Matcher;

class Bookmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'peergroup_id',
        'url',
        'title',
    ];

    public static function rules()
    {
        $updateRules = [
            'url' => ['array', 'max:20'],
            'title' => ['array', 'max:20'],
            'url.*' => ['string', 'url', 'max:255'],
            'title.*' => ['nullable', 'string', 'max:255'],
        ];

        return [
            'update' => $updateRules,
            'create' => $updateRules,
        ];
    }

    protected static function newFactory()
    {
        return new BookmarkFactory();
    }

    public function peergroup()
    {
        return $this->belongsTo(Peergroup::class);
    }    
}
