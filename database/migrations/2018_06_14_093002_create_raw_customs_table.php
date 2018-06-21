<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRawCustomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_customs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('identifier','32')->nullable()->comment('工单编号');
            $table->string('name','32')->nullable()->comment('客户名称');
            $table->string('tel', '16')->nullable()->comment('客户手机号');
            $table->tinyInteger('source')->nullable()->comment('客户来源,1=>400电话,2=>官网客服,3=>百度信息流,4=>今日头条信息流');
            $table->tinyInteger('demand')->nullable()->comment('需求类型,1=>投放房源,2=>委托找房');
            $table->string('position','32')->nullable()->comment('区域或楼盘');
            $table->decimal('acreage')->nullable()->comment('面积');
            $table->decimal('price')->nullable()->comment('价格');
            $table->integer('shopkeeper_id')->nullable()->comment('分配店长id');
            $table->integer('shopkeeper_deal')->nullable()->comment('店长处理时间');
            $table->integer('staff_id')->nullable()->comment('员工id');
            $table->integer('staff_deal')->nullable()->comment('员工确定时间');
            $table->text('remark')->nullable()->comment('备注');
            $table->string('recorder','32')->nullable()->comment('录入人');
            $table->timestamps();
        });
        \DB::statement("alter table `raw_customs` comment'原始客源表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raw_customs');
    }
}
