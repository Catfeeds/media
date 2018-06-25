<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCustomsSourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customs', function (Blueprint $table) {
            $table->integer('source')->default(1)->comment('来源: 1: 朋友 2: 客户 3: 同行 4: 赶集 5: 安居客 6: 58 7: 扫楼 8: 来访 9: 百度信息流 10: 今日头条信息流 11: 400电话 12: 官网客服 13: app 14: pc 15: 微信')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customs', function (Blueprint $table) {
            $table->tinyInteger('source')->default(1)->comment('来源: 1: 来电 2: 来访 3: 中介 4: 友 5: 告 6: 街 7: 网络')->change();
        });
    }
}
