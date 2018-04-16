<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDwellingHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dwelling_houses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('building_blocks_id')->nullable()->comment('楼座id');
            $table->string('house_number', 32)->nullable()->comment('房号');
            $table->string('owner_info', 1024)->nullable()->comment('业主联系方式:json');
            // 房子信息
            $table->tinyInteger('room')->nullable()->comment('N室');
            $table->tinyInteger('hall')->nullable()->comment('N厅');
            $table->tinyInteger('toilet')->nullable()->comment('N卫');
            $table->tinyInteger('kitchen')->nullable()->comment('N厨');
            $table->tinyInteger('balcony')->nullable()->comment('N阳台');
            $table->string('constru_acreage', 32)->nullable()->comment('建筑面积');
            $table->string('actual_acreage', 32)->nullable()->comment('使用面积');
            $table->tinyInteger('renovation')->nullable()->comment('装修');
            $table->string('orientation', 32)->nullable()->comment('朝向');
            $table->string('feature_lable', 1024)->nullable()->comment('特色标签:json');
            $table->string('support_facilities', 1024)->nullable()->comment('配套设施:json');
            $table->string('house_description', 255)->nullable()->comment('房源描述');

            // 租赁信息
            $table->decimal('rent_price', 10, 2)->nullable()->comment('租金');
            $table->string('payment_type', 32)->nullable()->comment('支付方式');
            $table->string('renting_style', 32)->nullable()->comment('出租方式');
            $table->integer('check_in_time')->nullable()->comment('入住时间');
            $table->string('shortest_lease', 32)->nullable()->comment('最短租期');
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
        Schema::dropIfExists('dwelling_houses');
    }
}
