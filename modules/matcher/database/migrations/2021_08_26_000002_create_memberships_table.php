<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipsTable extends Migration
{
    public function up()
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peergroup_id');
            $table->foreignId('user_id');
            $table->date('begin')->nullable();
            $table->text('comment')->nullable();
            $table->boolean('approved')->default(false);
            $table->timestamps();
        });

        Schema::create('language_membership', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id');
            $table->foreign('language_id')->references('id')->on('languages');
            $table->foreignId('membership_id');
            $table->foreign('membership_id')->references('id')->on('memberships');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('language_membership');
        Schema::dropIfExists('memberships');
    }
}