<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Companies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('companies')->create('voity_dev_companies.brand', function(Blueprint $table){
            $table->bigIncrements('brand_id');
            $table->string('brand_name');
            $table->bigInteger('brand_company_id');
            $table->bigInteger('brand_type_id');
        });

        Schema::connection('companies')->create('voity_dev_companies.cities', function(Blueprint $table){
            $table->bigIncrements('city_id');
            $table->string('name');
            $table->double('longitude');
            $table->double('latitude');
        });

        Schema::connection('companies')->create('voity_dev_companies.companies', function(Blueprint $table){
            $table->bigIncrements('company_id');
            $table->string('company_name');
            $table->integer('register_time');
        });

        Schema::connection('companies')->create('voity_dev_companies.descriptions', function(Blueprint $table){
            $table->bigIncrements('description_id');
            $table->longText('description');
        });

        Schema::connection('companies')->create('voity_dev_companies.happenings', function(Blueprint $table){
            $table->bigIncrements('happenings_id');
            $table->bigInteger('h_place_id');
            $table->string('happenings_name');
            $table->text('short_desc');
            $table->bigInteger('h_desc_id');
            $table->integer('start_date');
            $table->integer('complete_date');
            $table->bigInteger('h_company_id');
            $table->string('choise_name');
        });

        Schema::connection('companies')->create('voity_dev_companies.managers', function(Blueprint $table){
            $table->bigIncrements('manager_id');
            $table->string('login');
            $table->string('password');
            $table->bigInteger('managers_company_id');
            $table->integer('status');
            $table->integer('register_time');
            $table->integer('last_act_time');
        });

        Schema::connection('companies')->create('voity_dev_companies.place', function(Blueprint $table){
            $table->bigIncrements('place_id');
            $table->bigInteger('place_company_id');
            $table->string('place_name');
            $table->integer('place_city_id');
            $table->double('longitude');
            $table->double('latitude');
        });

        Schema::connection('companies')->create('voity_dev_companies.templates', function(Blueprint $table){
            $table->bigIncrements('template_id');
            $table->json('template');
            $table->integer('template_type_id');
        });

        Schema::connection('companies')->create('voity_dev_companies.template_data', function(Blueprint $table){
            $table->bigIncrements('td_id');
            $table->string('header_box');
            $table->longtext('logo');
            $table->json('background');
            $table->integer('td_brand_id');
            $table->json('excluded_btn');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('companies')->dropIfExists('voity_dev_companies.brand');
        Schema::connection('companies')->dropIfExists('voity_dev_companies.cities');
        Schema::connection('companies')->dropIfExists('voity_dev_companies.companies');
        Schema::connection('companies')->dropIfExists('voity_dev_companies.descriptions');
        Schema::connection('companies')->dropIfExists('voity_dev_companies.happenings');
        Schema::connection('companies')->dropIfExists('voity_dev_companies.managers');
        Schema::connection('companies')->dropIfExists('voity_dev_companies.place');
        Schema::connection('companies')->dropIfExists('voity_dev_companies.templates');
        Schema::connection('companies')->dropIfExists('voity_dev_companies.template_data');
    }
}
