<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('group_requests', function(Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->uuid('identifier')->index();
            $table->string('title');
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('language_group_request', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id');
            $table->foreign('language_id')->references('id')->on('languages');
            $table->foreignId('group_request_id');
            $table->foreign('group_request_id')->references('id')->on('group_requests');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('language_group_request');
        Schema::dropIfExists('group_requests');
    }
}