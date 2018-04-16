<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildingBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('building_blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->char('building_id', 32)->comment('所属楼盘id');
            $table->string('name', 32)->comment('楼座名称');
            $table->integer('class')->nullable()->comment('等级 1：甲 2：乙 3：丙');
            $table->integer('structure')->nullable()->comment('房屋结构 1：钢筋混凝土结构 2：钢结构 3：砖混结构 4：砖木结构');
            $table->integer('total_floor')->nullable()->comment('楼层总数量');
            $table->string('property_company', 128)->nullable()->comment('物业公司');
            $table->decimal('property_fee')->nullable()->comment('物业费');
            $table->integer('heating')->nullable()->comment('采暖方式 1：空调 2：太阳能');
            $table->integer('air_conditioner')->nullable()->comment('空调类型 1：中央空调 2：非中央空调');

            $table->integer('passenger_lift')->nullable()->comment('客梯数量');
            $table->integer('cargo_lift')->nullable()->comment('货梯数量');
            $table->integer('president_lift')->nullable()->comment('总裁电梯数量');


            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("alter table `building_blocks` comment'楼座表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('building_blocks');
    }
}
