<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRestrictInvitationsToPeergroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peergroups', function (Blueprint $table) {
            $table->boolean('restrict_invitations')->default(false);
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
                'restrict_invitations',
            ]);
        });
    }
}
