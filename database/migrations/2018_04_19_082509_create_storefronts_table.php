<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorefrontsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storefronts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('storefront_name', 32)->nullable()->comment('门店名称');
            $table->string('address', 32)->nullable()->comment('门店地址');
            $table->integer('user_id')->nullable()->comment('店长id');
            $table->string('fixed_tel',16)->nullable()->comment('座机');
            $table->timestamps();
            $table->softDeletes();
        });
        \DB::statement("alter table `storefronts` comment'门店表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storefronts');
    }
}
