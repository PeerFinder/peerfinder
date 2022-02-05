<?php

namespace Matcher\Models;

use App\Helpers\Facades\Urler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Matcher\Database\Factories\GroupTypeFactory;

class GroupType extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($groupType) {
            Urler::createUniqueSlug($groupType, 'identifier');
        });
    }

    protected static function newFactory()
    {
        return new GroupTypeFactory();
    }

    public function groupTypes()
    {
        return $this->hasMany(GroupType::class)->with('groupTypes')->orderBy('title_' . app()->getLocale());
    }

    public function groupType()
    {
        return $this->belongsTo(GroupType::class);
    }

    public function getRecursiveTypes()
    {
        $types = array($this);

        if ($this->group_type_id) {
            $types = array_merge($types, $this->groupType()->first()->getRecursiveTypes());
        }

        return $types;
    }

    public function title()
    {
        $field = 'title_' . app()->getLocale();
        return $this->$field;
    }

    public function description()
    {
        $field = 'description_' . app()->getLocale();
        return $this->$field;
    }

    public function reference()
    {
        $field = 'reference_' . app()->getLocale();
        return $this->$field;
    }
}