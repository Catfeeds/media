<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreaLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('area_id')->nullable()->comment('区域id');
            $table->string('x',32)->nullable()->comment('经度');
            $table->string('y',32)->nullable()->comment('纬度');
            $table->text('scope')->nullable()->comment('商圈范围');
            $table->integer('building_num')->nullable()->comment('楼盘套数');
            $table->timestamps();
            $table->softDeletes();
        });
        \DB::statement("alter table `area_locations` comment'区域地理范围基础数据表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('area_locations');
    }
}
