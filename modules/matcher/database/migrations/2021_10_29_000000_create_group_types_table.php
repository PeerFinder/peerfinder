<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupTypesTable extends Migration
{
    public function up()
    {
        Schema::create('group_types', function (Blueprint $table) {
            $table->id();
            $table->string('title_de');
            $table->string('title_en');
            $table->text('description_de');
            $table->text('description_en');
            $table->string('reference_de')->nullable();
            $table->string('reference_en')->nullable();
            $table->foreignId('group_type_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_types');
    }
}