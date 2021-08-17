<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationsTable extends Migration
{
    public function up()
    {
        Schema::create('conversations', function(Blueprint $table) {
            $table->id();
            $table->string('identifier')->index();
            $table->nullableMorphs('conversationable');
            $table->foreignId('user_id')->nullable();
            $table->foreignId('participant_id')->nullable();
            $table->string('title');
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('conversations');
    }
}