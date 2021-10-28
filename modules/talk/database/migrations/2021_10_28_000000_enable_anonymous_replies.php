<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnableAnonymousReplies extends Migration
{
    public function up()
    {
        Schema::table('replies', function(Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('replies', function(Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
}