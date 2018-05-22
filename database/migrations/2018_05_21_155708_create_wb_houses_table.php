<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWbHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wb_houses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wb_house_id')->nullable()->comment('58房源id');
            $table->text('title')->nullable()->comment('房源标题');
            $table->string('constru_acreage', 32)->nullable()->comment('建筑面积');
            $table->decimal('unit_price', 10, 2)->nullable()->comment('租金单价');
            $table->decimal('total_price', 10, 2)->nullable()->comment('租金总价');
            $table->string('house_description', 255)->nullable()->comment('房源描述');
            $table->json('indoor_img')->nullable()->comment('室内图:json');
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("alter table `wb_houses` comment'58房源数据'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wb_houses');
    }
}
