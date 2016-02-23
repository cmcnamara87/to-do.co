<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityFeaturePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_feature', function (Blueprint $table) {
            $table->integer('activity_id')->unsigned()->index();
//            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
            $table->integer('feature_id')->unsigned()->index();
//            $table->foreign('feature_id')->references('id')->on('features')->onDelete('cascade');
            $table->primary(['activity_id', 'feature_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('activity_feature');
    }
}
