<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tel')->nullable();
            $table->string('real_name')->nullable()->comment('真实名称');
            $table->string('nick_name')->nullable()->comment('昵称');
            $table->string('ascription_store')->nullable()->comment('所属门店');
            $table->tinyInteger('level')->nullable()->comment('级别 1: 总经理 2: 区域经理 3: 店长 4: 业务');
            $table->string('password');
            $table->text('remark')->nullable()->comment('备注信息');
            $table->rememberToken();
            $table->timestamps();
        });
        DB::statement("alter table `users` comment'用户表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
