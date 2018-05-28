<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuildingBlockElevatorNumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('building_blocks', function (Blueprint $table) {
            $table->integer('elevator_num')->nullable()->comment('电梯总数量')->after('air_conditioner');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('building_blocks', function (Blueprint $table) {
            $table->dropColumn('elevator_num');
        });
    }
}
