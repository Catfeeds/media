<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('house_id')->nullable()->comment('房源id');
            $table->integer('custom_id')->nullable()->comment('房源id');

            $table->string('tracks_mode', 32)->nullable()->comment('跟进方式');
            $table->string('tracks_mode', 32)->nullable()->comment('跟进方式');


            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("alter table `tracks` comment'跟进表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracks');
    }
}
