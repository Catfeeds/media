<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTmpHousesTable extends Migration
{
    /**
     * 中介老数据表
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmp_houses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('编号', 255)->nullable();
            $table->string('交易', 255)->nullable();
            $table->string('城区', 255)->nullable();
            $table->string('楼盘字典', 255)->nullable();
            $table->string('栋座位置', 255)->nullable();
            $table->string('房号', 255)->nullable();
            $table->string('楼层', 255)->nullable();
            $table->string('房型', 255)->nullable();
            $table->string('面积', 255)->nullable();
            $table->string('朝向', 255)->nullable();
            $table->string('售价', 255)->nullable();
            $table->string('租价', 255)->nullable();
            $table->string('装修', 255)->nullable();
            $table->string('用途', 255)->nullable();
            $table->string('委托', 255)->nullable();
            $table->string('备注', 255)->nullable();
            $table->string('业主', 255)->nullable();
            $table->string('联系人', 255)->nullable();
            $table->string('手机', 255)->nullable();
            $table->string('联系', 255)->nullable();
            $table->string('部门', 255)->nullable();
            $table->string('员工', 255)->nullable();
            $table->timestamps();
            \DB::statement("alter table `storefronts` comment'中介老数据表'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tmp_houses');
    }
}
