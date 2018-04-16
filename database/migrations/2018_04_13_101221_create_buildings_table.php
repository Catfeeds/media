<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;

class CreateBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 128)->nullable()->comment('楼盘名');
            $table->string('gps')->nullable()->comment('gps定位');
            $table->tinyInteger('type')->nullable()->comment('1:住宅 2：写字楼 3：商铺 4：商住两用' );

            $table->tinyInteger('street_id')->nullable()->comment('关联街道id');
            $table->tinyInteger('block_id')->nullable()->comment('商圈id');
            $table->string('address', 128)->nullable()->comment('具体地址');

            $table->string('developer', 128)->nullable()->comment('开发商');
            $table->integer('years')->nullable()->comment('年代 --年');
            $table->decimal('acreage')->nullable()->comment('建筑面积 --㎡');
            $table->integer('building_block_num')->nullable()->comment('楼栋数量 --栋');
            $table->integer('parking_num')->nullable()->comment('车位数量 --个');
            $table->decimal('parking_fee')->nullable()->comment('停车费 --元/月');
            $table->integer('greening_rate')->nullable()->comment('绿化率 --%');

            $table->json('company')->nullable()->comment('入驻企业');
            $table->json('album')->nullable()->comment('楼盘相册');

            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement("alter table `buildings` comment'楼盘表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buildings');
    }
}
