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
            $table->text('token');
            $table->text('event_id');
            $table->string('division');
            $table->string('vid');
            $table->string('rating');
            $table->string('callsign');
            $table->string('aircraft');
            $table->string('rule');
            $table->string('type');
            $table->string('load');
            $table->time('time_departure');
            $table->time('time_arrival');
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
