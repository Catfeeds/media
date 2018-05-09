<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOfficeBuildingHousesPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_building_houses', function (Blueprint $table) {
            $table->decimal('unit_price', 10, 2)->nullable()->comment('租金单价');
            $table->decimal('total_price',10,2)->nullable()->comment('租金总价');
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
            $table->dropColumn('unit_price');
            $table->dropColumn('total_price');
        });
    }
}
