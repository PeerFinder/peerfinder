<?php

namespace GroupRequests\Models;

use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use GroupRequests\Database\Factories\GroupRequestFactory;

class GroupRequest extends Model
{
    use HasFactory;

    public static function rules()
    {
        $updateRules = [
            'title' => ['string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
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
        });
    }

    protected static function newFactory()
    {
        return new GroupRequestFactory();
    }
}
