<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * 门店中的组
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('storefront_id')->comment('所属门店id');
            $table->integer('user_id')->nullable()->comment('组长id');
            $table->string('name')->comment('组名');
            $table->timestamps();
        });
        DB::statement("alter table `users` comment'门店中的组'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
