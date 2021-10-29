<?php

namespace Matcher\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Matcher\Database\Factories\GroupTypeFactory;

class GroupType extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return new GroupTypeFactory();
    }

    public function groupTypes()
    {
        return $this->hasMany(GroupType::class);
    }    

    public function groupType()
    {
        return $this->belongsTo(GroupType::class);
    }
}