<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRawCustoms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raw_customs', function (Blueprint $table) {
            $table->tinyInteger('valid')->nullable()->comment('是否有效，1：有效,2:无效')->after('feedback');
            $table->tinyInteger('clinch')->nullable()->comment('是否成交，1：成交，2：未成交')->after('valid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raw_customs', function (Blueprint $table) {
            //
        });
    }
}
