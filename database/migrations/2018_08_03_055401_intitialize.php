<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Intitialize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_key', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key');
            $table->string('division');
            $table->boolean('isallowed');
            $table->timestamps();
            $table->SoftDeletes();
        });
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->text('token');
            $table->text('event_id');
            $table->text('event_name');
            $table->timestamp('booktime_start');
            $table->timestamp('booktime_end');
            $table->string('airport_departure');
            $table->string('airport_arrival');
            $table->timestamps();
            $table->SoftDeletes();
        });
        Schema::create('flights', function (Blueprint $table) {
            $table->increments('id');
            $table->text('event_id');
            $table->string('user_division')->nullable();
            $table->string('user_vid')->nullable();
            $table->string('user_rating')->nullable();
            $table->string('aircraft_callsign');
            $table->string('aircraft_model')->nullable();
            $table->string('flight_rule')->nullable();
            $table->string('flight_type')->nullable();
            $table->string('flight_load')->nullable();
            $table->time('time_departure')->nullable();
            $table->time('time_arrival')->nullable();
            $table->timestamps();
            $table->SoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('access_key');
        Schema::drop('events');
        Schema::drop('flights');
    }
}
