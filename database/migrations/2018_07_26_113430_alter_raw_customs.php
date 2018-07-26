<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRawCustoms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raw_customs', function (Blueprint $table) {
        });
        \DB::statement("ALTER TABLE raw_customs CHANGE COLUMN source source TINYINT comment '客户来源,1:400电话,2:官网客服,3:百度信息流,4:今日头条信息流,5:app,6:PC,7:微信,8:小程序,9:58同城'");
        \DB::statement("ALTER TABLE raw_customs CHANGE COLUMN demand demand TINYINT comment '需求类型,1:投放房源,2:委托找房,3:企业服务,4:其他'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raw_customs', function (Blueprint $table) {
            //
        });
    }
}
