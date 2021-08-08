<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExtendUserProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('slogan')->nullable();
            $table->text('about')->nullable();
            $table->string('homepage')->nullable();
            $table->string('company')->nullable();
            $table->string('facebook_profile_url')->nullable();
            $table->string('twitter_profile_url')->nullable();
            $table->string('linkedin_profile_url')->nullable();            
            $table->string('xing_profile_url')->nullable();
            $table->string('avatar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'slogan',
                'about',
                'homepage',
                'company',
                'facebook_profile_url',
                'twitter_profile_url',
                'linkedin_profile_url',
                'xing_profile_url',
                'avatar',      
            ]);
        });
    }
}
