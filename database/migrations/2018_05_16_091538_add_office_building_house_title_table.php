<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOfficeBuildingHouseTitleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_building_houses', function (Blueprint $table) {
            $table->text('title')->nullable()->comment('房源标题')->after('house_number');
            $table->tinyInteger('shelf')->nullable()->comment('是否上架 1: 是 2: 否')->after('indoor_img');
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
            $table->dropColumn('describe');
            $table->dropColumn('shelf');
        });
    }
}
