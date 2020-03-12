<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Flights extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('flight_time');  //Time of flight. You'll have LAT/LONG so you can adjust into UTC if you wish
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('long', 11, 8)->nullable();
            $table->integer('duration_in_seconds');
            $table->text('notes');
            $table->boolean('warning')->nullable();
            $table->char('weatherSummary', 50)->nullable();
            $table->char('weatherTemperature', 25)->nullable();
            $table->char('advisoryColor', 25)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
