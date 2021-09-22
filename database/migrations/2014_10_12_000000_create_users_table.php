<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('voity_dev_users.users', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('login')->unique();
            $table->string('password');
            $table->string('phone')->unique();
            $table->string('api_token', 80)
                        ->unique()
                        ->nullable()
                        ->default(null);
            $table->integer('register_time');
            $table->integer('last_act_time');
            $table->boolean('phone_verified')->default(FALSE);
            $table->integer('status')->default(0);
            $table->bigInteger('data_id')->default(-1);
            $table->bigInteger('current_place')->default(0);
            $table->integer('internal_credits')->default(0);
        });

        Schema::connection('mysql')->create('voity_dev_users.place_history', function (Blueprint $table) {
            $table->bigIncrements('place_history_id');
            $table->bigInteger('place_id');
            $table->bigInteger('place_history_user_id');
        });

        Schema::connection('mysql')->create('voity_dev_users.my_places', function (Blueprint $table) {
            $table->bigIncrements('my_places_id');
            $table->bigInteger('my_places_user_id');
            $table->json('place_id_array');
        });

        Schema::connection('mysql')->create('voity_dev_users.user_referral_hashes', function(Blueprint $table){
            $table->bigIncrements('urh_id');
            $table->bigInteger('urh_user_id');
            $table->string('hash');
        });

        Schema::connection('mysql')->create('voity_dev_users.inviters', function(Blueprint $table){
            $table->bigIncrements('inviters_id');
            $table->bigInteger('inviter_user_id');
            $table->bigInteger('referral_id');
        }); 

        Schema::connection('mysql')->create('voity_dev_users.intermetiate_registration', function(Blueprint $table){
            $table->bigIncrements('ir_id');
            $table->string('ip');
            $table->string('hash');
        });

        Schema::connection('mysql')->create('voity_dev_users.links', function(Blueprint $table){
            $table->bigIncrements('linkid');
            $table->bigInteger('ir_hid');
            $table->string('link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->dropIfExists('voity_dev_users.users');
        Schema::connection('mysql')->dropIfExists('voity_dev_users.my_places');
        Schema::connection('mysql')->dropIfExists('voity_dev_users.place_history');
        Schema::connection('mysql')->dropIfExists('voity_dev_users.user_referral_hashes');
        Schema::connection('mysql')->dropIfExists('voity_dev_users.inviters');
        Schema::connection('mysql')->dropIfExists('voity_dev_users.intermetiate_registration');
        Schema::connection('mysql')->dropIfExists('voity_dev_users.links');
    }
}
