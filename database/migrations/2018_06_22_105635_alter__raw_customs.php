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
            $table->string('acreage','32')->nullable()->comment('面积')->change();
            $table->string('price','32')->nullable()->comment('价格')->change();
        });
        \DB::statement("ALTER TABLE raw_customs CHANGE COLUMN source source  TINYINT  comment '客户来源,1=>400电话,2=>官网客服,3=>百度信息流,4=>今日头条信息流,5=>app,6=>PC,7=>微信'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('RawCustoms', function (Blueprint $table) {
            //
        });
    }
}
