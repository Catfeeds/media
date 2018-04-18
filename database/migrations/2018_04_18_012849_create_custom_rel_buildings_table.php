<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomRelBuildingsTable extends Migration
{
    /**
     * 客户意向楼盘
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_rel_buildings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('custom_id')->comment('客户id');
            $table->integer('building_id')->comment('楼盘id');
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
        Schema::dropIfExists('custom_rel_buildings');
    }
}
