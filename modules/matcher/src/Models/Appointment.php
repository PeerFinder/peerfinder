<?php

namespace Matcher\Models;

use App\Helpers\Facades\Urler;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
            'location' => ['required', 'string', 'max:255'],
            'details' => ['required', 'string', 'max:800'],
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
}
