<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeergroupsTable extends Migration
{
    public function up()
    {
        Schema::create('peergroups', function(Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->string('groupname')->unique();
            $table->string('title');
            $table->text('description');
            $table->integer('limit')->unsigned();
            $table->date('begin')->nullable();
            $table->boolean('virtual')->default(true);
            $table->boolean('private')->default(false);
            $table->boolean('open')->default(true);
            $table->boolean('with_approval')->default(false);
            $table->string('location');
            $table->string('meeting_link');
            $table->timestamps();
        });

        Schema::create('language_peergroup', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id');
            $table->foreign('language_id')->references('id')->on('languages');
            $table->foreignId('peergroup_id');
            $table->foreign('peergroup_id')->references('id')->on('peergroups');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('language_peergroup');
        Schema::dropIfExists('peergroups');
    }
}