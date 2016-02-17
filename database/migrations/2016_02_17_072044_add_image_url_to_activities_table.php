<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImageUrlToActivitiesTable extends Migration
{
    public function up()
    {
        Schema::table('activities', function(Blueprint $table)
        {
            $table->string('image_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activities', function(Blueprint $table)
        {
            $table->dropColumn('image_url');
        });
    }
}
