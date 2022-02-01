<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Matcher\Models\Appointment;

class AddDurationToAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dateTime('end_date')->after('date')->nullable();
        });

        Appointment::all()->each(function ($appointment) {
            $appointment->end_date = $appointment->date->addHour();
            $appointment->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'end_date',
            ]);
        });
    }
}
