<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepliesTable extends Migration
{
    public function up()
    {
        Schema::create('replies', function(Blueprint $table) {
            $table->id();
            $table->uuid('identifier')->index();
            $table->text('message');
            $table->foreignId('user_id');
            $table->foreignId('conversation_id');
            $table->foreignId('reply_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('replies');
    }
}