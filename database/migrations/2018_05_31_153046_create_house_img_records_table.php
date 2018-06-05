<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHouseImgRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house_img_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->comment('用户id');
            $table->string('model',32)->nullable()->comment('房源model');
            $table->integer('house_id')->nullable()->comment('房源id');
            $table->json('indoor_img')->nullable()->comment('修改房源的图片');
            $table->tinyInteger('status')->default(1)->comment('审核状态 1: 审核中 2: 审核失败 3: 审核通过');
            $table->timestamps();
        });
        DB::statement("alter table `house_img_records` comment'房源图片记录表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('house_img_records');
    }
}
