<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_seats', function (Blueprint $table) {
            $table->id();
            $table->integer('booking');
            $table->integer('seat');
            $table->string('turn');
            $table->integer('start');
            $table->integer('end');
            $table->dateTime('starttime');
            $table->dateTime('endtime');
            $table->tinyInteger('status')->default(1); //1 active - 2 arrived - 3 departured - 4 deleted
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_seats');
    }
}
