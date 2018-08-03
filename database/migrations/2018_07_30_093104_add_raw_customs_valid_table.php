<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRawCustomsValidTable extends Migration
{
    public function up()
    {
        Schema::table('raw_customs', function (Blueprint $table) {
            $table->tinyInteger('valid')->nullable()->comment('是否有效，1：有效,2:无效')->after('feedback');
            $table->tinyInteger('clinch')->nullable()->comment('是否成交，1：成交，2：未成交')->after('valid');
            $table->integer('source')->nullable()->comment('客户来源,1:400电话,2:官网客服,3:百度信息流,4:今日头条信息流,5:app,6:PC,7:微信,8:小程序,9:58同城')->change();
            $table->integer('demand')->nullable()->comment('需求类型,1:投放房源,2:委托找房,3:企业服务,4:其他')->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raw_customs', function (Blueprint $table) {
            $table->dropColumn('valid');
            $table->dropColumn('clinch');
            $table->tinyInteger('source')->nullable()->comment('客户来源,1=>400电话,2=>官网客服,3=>百度信息流,4=>今日头条信息流,5=>app,6=>PC,7=>微信')->change();
            $table->tinyInteger('demand')->nullable()->comment('需求类型,1=>投放房源,2=>委托找房')->change();

        });



    }
}
