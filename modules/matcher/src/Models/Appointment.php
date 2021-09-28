<?php

namespace Matcher\Models;

use App\Helpers\Facades\EasyDate;
use App\Helpers\Facades\Urler;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Matcher\Database\Factories\AppointmentFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'details',
        'date',
        'time',
        'location',
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public static function rules()
    {
        $updateRules = [
            'subject' => ['required', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:255'],
            'details' => ['nullable', 'string', 'max:800'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
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
    }

    protected static function newFactory()
    {
        return new AppointmentFactory();
    }

    public function peergroup()
    {
        return $this->belongsTo(Peergroup::class);
    }

    public function fullDate($user_tz = false)
    {
        return EasyDate::joinDateTime($this->date, $this->time, $user_tz);
    }

    public function isInPast()
    {
        $currentUserTime = Carbon::now();

        return $currentUserTime->diffInSeconds($this->fullDate(), false) < 0;
    }
}
