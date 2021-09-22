<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VoityConnections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('connections')->create('voity_dev_connections.connections', function ($table) {
            $table->bigIncrements('connection_id');
            $table->string('host');
            $table->integer('port');
            $table->string('database');
            $table->string('username');
            $table->string('password');
            $table->integer('city_id');
            $table->integer('place_id');
            $table->integer('company_id');
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
    }
}
