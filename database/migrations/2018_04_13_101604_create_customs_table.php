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
            $table->tinyInteger('status')->comment('客源状态 1-有效 2-暂缓 3-成交 4-无效')->nullable();
            $table->tinyInteger('class')->comment('客户等级 1-A 2-B 3-C')->nullable();
            $table->tinyInteger('source')->default(1)->comment('来源: 1: 来电 2: 来访 3: 中介 4: 友 5: 告 6: 街 7: 网络');
            $table->tinyInteger('belong')->comment('公私盘：1：公盘 2：私盘')->nullable();
            $table->string('name', 32)->comment('客户姓名')->nullable();
            $table->string('tel', 32)->comment('客户电话')->nullable();

            $table->integer('price_low')->comment('最低承受价格： n元')->nullable();
            $table->integer('price_high')->comment('最高承受价格： n元')->nullable();

            $table->tinyInteger('payment_type')->default(1)->comment('支付方式: 1: 押一付一 2: 押一付二 3: 押一付三 4: 押二付一 5: 押二付二 6: 押二付三 7: 押三付一 8: 押三付二 9: 押三付三 10: 半年付 11: 年付 12: 面谈');
            $table->decimal('pay_commission', 10, 2)->nullable()->comment('付佣');
            $table->tinyInteger('pay_commission_unit')->default(1)->comment('付佣单位: 1: % 2: 多少元');

            $table->tinyInteger('need_type')->comment('求租类型：1-住宅 2-写字楼 3-商铺')->nullable();
            $table->tinyInteger('renting_style')->default(1)->comment('住宅： 出租方式: 1: 整租 2: 合租');
            $table->tinyInteger('office_building_type')->default(1)->comment('写字楼： 写字楼类型 1: 纯写字楼 2: 商住楼 3: 商业综合体楼 4: 酒店写字楼 5: 其他');
            $table->tinyInteger('shops_type')->default(1)->comment('商铺类型 1: 住宅底商 2: 商业街商铺 3: 酒店商底 4: 社区商铺 5: 沿街商铺 6: 写字底商 7: 购物中心 8: 旅游商铺 9: 其他');

            // 客户需求
            $table->integer('area_id')->comment('意向区id： n ㎡')->nullable();
            $table->integer('acre_low')->comment('最低需求面积： n ㎡')->nullable();
            $table->integer('acre_high')->comment('最高需求面积： n㎡')->nullable();
            $table->integer('room')->comment('需求 n室')->nullable();
            $table->integer('hall')->comment('需求 n厅')->nullable();
            $table->integer('toilet')->comment('需求 n卫')->nullable();
            $table->integer('kitchen')->comment('需求 n厨')->nullable();
            $table->integer('balcony')->comment('需求 n阳台')->nullable();
            $table->integer('floor_low')->comment('最低需求楼层')->nullable();
            $table->integer('floor_high')->comment('最高需求楼层')->nullable();
            $table->tinyInteger('renovation')->nullable()->comment('装修: 1: 豪华装修 2: 精装修 3: 中等装修 4: 简装修 5: 毛坯');
            $table->tinyInteger('orientation')->default(1)->comment('朝向: 1: 东 2: 南 3: 西 4: 北 5: 东南 6: 西南 7: 东北 8: 西北');

            // 配套设置需求
            $table->tinyInteger('subway')->comment('期望地铁')->nullable();
            $table->integer('walk_to_subway')->comment('最近地铁站步行时间 N分钟')->nullable();
            $table->string('bus',128)->comment('公交线路')->nullable();
            $table->integer('walk_to_bus')->comment('最近公交站步行时间 N分钟')->nullable();
            $table->string('like',256)->comment('核心需求点')->nullable();
            $table->string('not_like',256)->comment('核心抵触点')->nullable();

            $table->json('other')->comment('其他需求')->nullable();

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
