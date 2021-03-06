<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops_houses', function (Blueprint $table) {
            // 核心信息
            $table->increments('id');
            $table->string('house_identifier', 32)->nullable()->comment('房源编号: 住宅房源以大写S开头+年月日+3个数字');
            $table->integer('building_block_id')->nullable()->comment('楼座id');
            $table->string('house_number', 32)->nullable()->comment('房号');
            $table->json('owner_info')->nullable()->comment('业主联系方式:json');
            // 房子信息
            $table->string('constru_acreage', 32)->nullable()->comment('建筑面积');
            $table->tinyInteger('split')->nullable()->comment('可拆分 1: 是 2: 否');
            $table->string('min_acreage', 32)->nullable()->comment('最小面积');
            $table->integer('floor')->nullable()->comment('楼层');
            $table->tinyInteger('frontage')->nullable()->comment('是否临街 1: 是 2: 否');
            $table->tinyInteger('shops_type')->nullable()->comment('商铺类型 1: 住宅底商 2: 商业街商铺 3: 酒店商底 4: 社区商铺 5: 沿街商铺 6: 写字底商 7: 购物中心 8: 旅游商铺 9: 其他');
            $table->tinyInteger('renovation')->nullable()->comment('装修: 1: 豪华装修 2: 精装修 3: 中装修 4: 间装修 5: 毛坯');
            $table->tinyInteger('orientation')->nullable()->comment('朝向: 1: 东 2: 西 3: 南 4: 北 5: 东南 6: 东北 7: 西南 8: 西北 9: 东西 10: 南北');
            $table->string('wide', 32)->nullable()->comment('面宽');
            $table->string('depth', 32)->nullable()->comment('进深');
            $table->string('storey', 32)->nullable()->comment('层高');
            $table->json('support_facilities')->nullable()->comment('配套设施: json');
            $table->json('fit_management')->nullable()->comment('适合经营: json');
            $table->string('house_description', 255)->nullable()->comment('房源描述');
            // 租赁信息
            $table->decimal('rent_price', 10, 2)->nullable()->comment('租金');
            $table->tinyInteger('rent_price_unit')->nullable()->comment('租金单位: 1: % 2: 多少元');
            $table->tinyInteger('payment_type')->nullable()->comment('支付方式: 1: 押一付一 2: 押一付二 3: 押一付三 4: 押二付一 5: 押二付二 6: 押二付三 7: 押三付一 8: 押三付二 9: 押三付三 10: 半年付 11: 年付 12: 面谈');
            $table->date('check_in_time')->nullable()->comment('入住时间');
            $table->tinyInteger('shortest_lease')->nullable()->comment('最短租期: 1: 1-2年 2: 2-3年 3: 3-4年 4: 5年以上');
            $table->tinyInteger('rent_free')->nullable()->comment('免租期: 1: 1个月 2: 2个月 3: 3个月 4: 4个月 5: 5个月 6: 6个月 7: 7个月 8: 8个月 9: 9个月 10: 10个月 11: 面谈');
            $table->string('increasing_situation', 32)->nullable()->comment('递增情况');
            $table->string('increasing_situation_remark', 256)->nullable()->comment('递增情况备注');
            $table->decimal('transfer_fee', 10, 2)->nullable()->comment('转让费');
            $table->string('transfer_fee_remark', 256)->nullable()->comment('转让费备注');
            $table->json('cost_detail')->nullable()->comment('费用明细:json');
            // 业务信息
            $table->tinyInteger('house_busine_state')->nullable()->comment('房源业务状态: 1: 有效 2: 暂缓 3: 已租 4: 收购 5: 托管 6: 无效');
            $table->decimal('pay_commission', 10, 2)->nullable()->comment('付佣');
            $table->tinyInteger('pay_commission_unit')->nullable()->comment('付佣单位: 1: % 2: 多少元');
            $table->tinyInteger('prospecting')->nullable()->comment('是否实勘 1: 是 2: 否');
            $table->tinyInteger('source')->nullable()->comment('来源: 1: 来电 2: 来访 3: 中介 4: 友 5: 告 6: 街 7: 网络 8:自有数据');
            $table->string('house_key',32)->nullable()->comment('钥匙');
            $table->tinyInteger('see_house_time')->nullable()->nullable()->comment('看房时间 1: 随时 2: 非工作时间 3: 电话预约');
            $table->string('see_house_time_remark',32)->nullable()->comment('看房时间备注');
            $table->tinyInteger('certificate_type')->nullable()->comment('证件类型: 1: 房地产证 2: 购房合同 3: 购房发票 4: 抵押合同 5: 认购书 6: 预售合同 7: 回迁合同');
            $table->tinyInteger('house_proxy_type')->nullable()->comment('房源状态: 1: 独家 2: 委托');
            $table->tinyInteger('guardian')->nullable()->comment('维护人');

            // 房源照片
            $table->json('house_type_img')->nullable()->comment('户型图:json');
            $table->json('indoor_img')->nullable()->comment('室内图:json');

            $table->softDeletes();
            $table->timestamps();
        });
        \DB::statement("alter table `shops_houses` comment'商铺房源'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops_houses');
    }
}
