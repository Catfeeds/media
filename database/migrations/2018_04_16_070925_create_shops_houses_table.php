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
            $table->integer('building_blocks_id')->nullable()->comment('楼座id');
            $table->string('house_number', 32)->nullable()->comment('房号');
            $table->string('owner_info', 1024)->nullable()->comment('业主联系方式:json');
            // 房子信息
            $table->integer('floor')->nullable()->comment('楼层');
            $table->tinyInteger('frontage')->default(1)->nullable()->comment('是否临街 1: 是 2: 否');
            $table->string('constru_acreage', 32)->nullable()->comment('建筑面积');
            $table->tinyInteger('split')->default(1)->nullable()->comment('可拆分 1: 是 2: 否');
            $table->string('min_acreage', 32)->nullable()->comment('最小面积');
            $table->tinyInteger('renovation')->nullable()->comment('装修');
            $table->tinyInteger('type')->nullable()->comment('类型');
            $table->string('orientation', 32)->nullable()->comment('朝向');
            $table->string('wide', 32)->nullable()->comment('面宽');
            $table->string('depth', 32)->nullable()->comment('进深');
            $table->string('storey', 32)->nullable()->comment('层高');
            $table->string('supporting', 1024)->nullable()->comment('配套');
            $table->string('fit_management', 1024)->nullable()->comment('适合经营');
            $table->string('house_description', 255)->nullable()->comment('房源描述');
            // 租赁信息
            $table->decimal('rent_price', 10, 2)->nullable()->comment('租金');
            $table->string('payment_type', 32)->nullable()->comment('支付方式');
            $table->integer('check_in_time')->nullable()->comment('入住时间');
            $table->string('shortest_lease', 32)->nullable()->comment('最短租期');
            $table->string('rent_free', 32)->nullable()->comment('免租期');
            $table->string('increasing_situation', 32)->nullable()->comment('递增情况');
            $table->decimal('transfer_fee', 10, 2)->nullable()->comment('转让费');
            $table->string('cost_detail', 1024)->nullable()->comment('费用明细:json');
            // 业务信息
            $table->string('house_nature',32)->nullable()->comment('房源性质');
            $table->string('source',32)->nullable()->comment('来源');
            $table->string('actuality',32)->nullable()->comment('现状');
            $table->decimal('payment', 10, 2)->nullable()->comment('付款');
            $table->decimal('pay_commission', 10, 2)->nullable()->comment('付佣');
            $table->integer('see_house_time')->nullable()->comment('看房时间');
            $table->integer('give_house_time')->nullable()->comment('交房时间');
            $table->string('certificate',32)->nullable()->comment('证件');
            $table->string('entrust_number',32)->nullable()->comment('委托编号');
            $table->string('house_key',32)->nullable()->comment('钥匙');
            $table->tinyInteger('prospecting')->default(1)->nullable()->comment('是否实勘 1: 是 2: 否');
            $table->string('guardian', 32)->nullable()->comment('维护人');

            // 房源照片
            $table->string('house_type_img', 1024)->nullable()->comment('户型图:json');
            $table->string('indoor_img', 1024)->nullable()->comment('室内图:json');

            $table->softDeletes();
            $table->timestamps();
        });
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
