<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInheritLocationToPeergroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peergroups', function (Blueprint $table) {
            $table->boolean('inherit_location')->default(false);
            $table->boolean('use_jitsi_for_location')->default(false);
            $table->string('jitsi_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peergroups', function (Blueprint $table) {
            $table->dropColumn([
                'inherit_location',
                'use_jitsi_for_location',
                'jitsi_url',
            ]);
        });
    }
}
