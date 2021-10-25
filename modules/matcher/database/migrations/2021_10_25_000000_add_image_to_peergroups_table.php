<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImageToPeergroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peergroups', function (Blueprint $table) {
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();
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
                'image',
                'image_alt',
            ]);
        });
    }
}
