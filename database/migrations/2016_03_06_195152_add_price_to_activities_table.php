<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriceToActivitiesTable extends Migration
{
    public function up()
    {
        Schema::table('activities', function(Blueprint $table)
        {
            $table->decimal('price', 5, 2);
            $table->decimal('value', 5, 2);
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
            $table->dropColumn('price');
            $table->dropColumn('value');
        });
    }
}
