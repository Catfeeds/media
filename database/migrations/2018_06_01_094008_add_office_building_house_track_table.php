<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOfficeBuildingHouseTrackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_building_houses', function (Blueprint $table) {
            $table->integer('start_track_time')->nullable()->comment('跟进时间')->after('shelf');
            $table->integer('end_track_time')->nullable()->comment('跟进结束时间')->after('start_track_time');
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
            $table->dropColumn('start_track_time');
            $table->dropColumn('end_track_time');
        });
    }
}
