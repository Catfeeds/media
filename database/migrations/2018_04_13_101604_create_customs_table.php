<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customs', function (Blueprint $table) {
            $table->increments('id');

            // 客源状态
            // 客户等级
            // 客户来源
            // 公司盘
            // 客户姓名
            // 客户电话
            // 联系人
            // 承受价格 n 元 到 n元
            // 付款房还是
            // 付佣
            // 租房类型

            // 客户需求
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("alter table `customs` comment'客户表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customs');
    }
}
