<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShopsHousesPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shops_houses', function (Blueprint $table) {
            $table->decimal('unit_price', 10, 2)->nullable()->comment('租金单价')->after('rent_price_unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shops_houses', function (Blueprint $table) {
            $table->dropColumn('unit_price');
        });
    }
}
