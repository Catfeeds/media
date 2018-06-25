<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOfficeBuildingHouses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_building_houses', function (Blueprint $table) {
            $table->string('gd_identifier','32')->nullable()->comment('工单编号')->after('house_identifier');
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
            //
        });
    }
}
