<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipsTable extends Migration
{
    public function up()
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('peergroup_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->date('begin')->nullable();
            $table->text('comment')->nullable();
            $table->bool('approved')->default(false);
            $table->timestamps();
        });

        Schema::create('language_membership', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('language_id')->unsigned();
            $table->foreign('language_id')->references('id')->on('languages');
            
            $table->integer('membership_id')->unsigned();
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