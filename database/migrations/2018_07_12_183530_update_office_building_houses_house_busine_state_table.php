<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOfficeBuildingHousesHouseBusineStateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_building_houses', function (Blueprint $table) {
            $table->integer('house_busine_state')->default(1)->comment('房源业务状态: 1: 有效 2: 信息不明确 3: 暂缓 4: 已租 5: 出售 6: 无效 7: 签约')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('office_building_houses', function (Blueprint $table) {
            $table->tinyInteger('house_busine_state')->default(1)->comment('房源业务状态: 1: 有效 2: 暂缓 3: 已租 4: 收购 5: 托管 6: 无效')->change();
        });
    }
}
