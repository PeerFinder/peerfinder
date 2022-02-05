<?php

namespace Matcher\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Matcher\Models\Appointment;
use Matcher\Models\Peergroup;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition()
    {
        $date = $this->faker->dateTime();

        return [
            'subject' => $this->faker->realText(50),
            'details' => $this->faker->realText(50),
            'location' => $this->faker->city(),
            'date' => $date,
            'end_date' => $date->modify('+60 minutes'),
        ];
    }

    public function forPeergroup(Peergroup $pg)
    {
        return $this->state(function ($attributes) use ($pg) {
            return [
                'peergroup_id' => $pg->id,
            ];
        });
    }
}