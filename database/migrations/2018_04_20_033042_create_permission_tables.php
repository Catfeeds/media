<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->increments('id')->comment('权限id');
            $table->string('guard_name');
            $table->string('name_en', 32)->nullable()->comment('权限英文名');
            $table->string('name_cn', 128)->nullable()->comment('权限中文名');
            $table->integer('group_id')->nullable()->comment('所属组id');

            $table->timestamps();
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->increments('id')->comment('角色id');
            $table->string('guard_name');
            $table->string('name_cn')->comment('角色中文名');
            $table->string('name_en')->comment('角色英文名');

            $table->timestamps();
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->integer('permission_id')->unsigned();
            $table->string('model', 32);

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->primary(['permission_id']);
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames) {
            $table->integer('role_id')->unsigned()->comment('角色id');
            $table->integer('user_id')->nullable()->comment('用户id');

//            $table->morphs('model');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['role_id', 'user_id']);
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->integer('permission_id')->unsigned()->comment('权限id');
            $table->integer('role_id')->unsigned()->comment('角色id');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);

            app('cache')->forget('spatie.permission.cache');
        });

        // 权限层级管理表
        Schema::create('permission_groups', function (Blueprint $table) {
            $table->char('group_name', 32)->comment('组名');
            $table->integer('id')->unsigned()->comment('权限组id');
            $table->integer('parent_id')->unsigned()->nullable()->comment('父级权限组id');
            $table->char('guard_name', 128)->comment('所属认证平台');
            $table->primary(['id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
        Schema::drop('permission_groups');
    }
}
