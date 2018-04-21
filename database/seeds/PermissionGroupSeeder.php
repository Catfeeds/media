<?php

use Illuminate\Database\Seeder;

use App\Models\PermissionGroup;

class PermissionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 管理员子账户管理
        PermissionGroup::create([
            'id' => 1,
            'group_name' => '房源管理',
            'parent_id' => null,
            'stage' => 1,
        ]);

        // 楼盘管理
        PermissionGroup::create([
            'id' => 2,
            'group_name' => '楼盘管理',
            'parent_id' => null,
            'stage' => 1,
        ]);

        // 资讯管理
        PermissionGroup::create([
            'id' => 3,
            'group_name' => '客户管理',
            'parent_id' => null,
            'stage' => 1,
        ]);

        // 权限组管理
        PermissionGroup::create([
            'id' => 4,
            'group_name' => '区域管理',
            'parent_id' => null,
            'stage' => 1,
        ]);

        // 权限管理
        PermissionGroup::create([
            'id' => 5,
            'group_name' => '门店管理',
            'parent_id' => null,
            'stage' => 1,
        ]);

        // 角色管理
        PermissionGroup::create([
            'id' => 6,
            'group_name' => '成员管理',
            'parent_id' => null,
            'stage' => 1,
        ]);
    }
}
