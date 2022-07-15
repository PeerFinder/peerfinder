<?php

namespace Matcher\Models;

use App\Helpers\Facades\EasyDate;
use App\Helpers\Facades\Urler;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Matcher\Database\Factories\AppointmentFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'details',
        'date',
        'end_date',
        'location',
    ];

    protected $casts = [
        'date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected $with = [
        'peergroup',
    ];

    public static function rules()
    {
        $updateRules = [
            'subject' => ['required', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:255'],
            'details' => ['nullable', 'string', 'max:800'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'end_date' => ['required', 'date'],
            'end_time' => ['required', 'date_format:H:i'],
        ];

        return [
            'update' => $updateRules,
            'create' => $updateRules,
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($appointment) {
            Urler::createUniqueSlug($appointment, 'identifier');
        });

        static::saving(function ($appointment) {
            if ($appointment->end_date <= $appointment->date) {
                $appointment->end_date = $appointment->date->addHour();
            }
        });
    }

    protected static function newFactory()
    {
        return new AppointmentFactory();
    }

    public function peergroup()
    {
        return $this->belongsTo(Peergroup::class);
    }

    public function isNow()
    {
        return Carbon::now()->betweenIncluded($this->date, $this->end_date);
    }    

    public function isInPast()
    {
        return Carbon::now()->isAfter($this->end_date);
    }

    protected function location() : Attribute
    {
        return Attribute::make(
            get: function($value) {
                if($this->peergroup->inherit_location) {
                    if ($this->peergroup->virtual) {
                        return $this->peergroup->meeting_link;
                    } else {
                        return $this->peergroup->location;
                    }
                } else {
                    return $value;
                }
            },
        );
    }
}
