<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomRelAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_rel_areas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('custom_id');
            $table->integer('area_id');
            $table->foreign('custom_id')->references('id')->on('customs');
            $table->foreign('area_id')->references('id')->on('areas');
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
        Schema::dropIfExists('custom_rel_areas');
    }
}
