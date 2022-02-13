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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}