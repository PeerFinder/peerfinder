<?php

namespace Matcher\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Matcher\Facades\Matcher;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Invitation extends Model
{
    use LogsActivity;

    public static function rules()
    {
        $updateRules = [
            'comment' => ['nullable', 'string', 'max:500'],
            'search_users' => 'required',
            'search_users.*' => 'exists:users,username',
        ];

        return [
            'update' => $updateRules,
            'create' => $updateRules,
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
