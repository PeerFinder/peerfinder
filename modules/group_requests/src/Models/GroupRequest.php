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
            'description' => ['nullable', 'string', 'max:255'],
        ];

        return [
            'update' => $updateRules,
            'create' => $updateRules,
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($request) {
        });

        static::deleting(function ($request) {
        });
    }

    protected static function newFactory()
    {
        return new GroupRequestFactory();
    }
}
