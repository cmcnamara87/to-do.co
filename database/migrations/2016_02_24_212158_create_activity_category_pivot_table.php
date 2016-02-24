<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityCategoryPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_category', function (Blueprint $table) {
            $table->integer('activity_id')->unsigned()->index();
//            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
            $table->integer('category_id')->unsigned()->index();
//            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->primary(['activity_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('activity_category');
    }
}
