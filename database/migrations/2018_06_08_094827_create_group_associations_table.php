<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupAssociationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_associations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('storefronts_id')->nullable()->comment('门店id');
            $table->string('name', 32)->nullable()->comment('组名');
            $table->integer('group_leader_id')->nullable()->comment('组长id');
            $table->timestamps();
            $table->softDeletes();
        });
        \DB::statement("alter table `group_associations` comment'组长业务员关联表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_associations');
    }
}
