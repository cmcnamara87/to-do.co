<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimetablesTable extends Migration
{
    public function up()
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->integer('activity_id');
            $table->dateTimeTz('start_time');
            $table->dateTimeTz('end_time');
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
        Schema::drop('timetables');
    }
}
