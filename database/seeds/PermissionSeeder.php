<?php

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 房源管理
        Permission::create([
            'name' => 'house_list',
            'guard_name' => 'web',
            'label' => '房源列表',
            'group_id' => 1,
        ]);

        Permission::create([
            'name' => 'add_house',
            'guard_name' => 'web',
            'label' => '房源添加',
            'group_id' => 1,
        ]);

        Permission::create([
            'name' => 'update_house',
            'guard_name' => 'web',
            'label' => '修改房源',
            'group_id' => 1,
        ]);

        Permission::create([
            'name' => 'del_house',
            'guard_name' => 'web',
            'label' => '删除房源',
            'group_id' => 1,
        ]);

        Permission::create([
            'name' => 'set_top_house',
            'guard_name' => 'web',
            'label' => '置顶房源',
            'group_id' => 1,
        ]);

        Permission::create([
            'name' => 'update_business_state',
            'guard_name' => 'web',
            'label' => '修改房源业务状态',
            'group_id' => 1,
        ]);

        Permission::create([
            'name' => 'house_add_tracks',
            'guard_name' => 'web',
            'label' => '添加跟进',
            'group_id' => 1,
        ]);

        // 楼盘管理
        Permission::create([
            'name' => 'building_list',
            'guard_name' => 'web',
            'label' => '楼盘列表',
            'group_id' => 2,
        ]);

        Permission::create([
            'name' => 'add_building',
            'guard_name' => 'web',
            'label' => '添加楼盘',
            'group_id' => 2,
        ]);

        Permission::create([
            'name' => 'update_building',
            'guard_name' => 'web',
            'label' => '修改楼盘',
            'group_id' => 2,
        ]);

        Permission::create([
            'name' => 'del_building',
            'guard_name' => 'web',
            'label' => '删除楼盘',
            'group_id' => 2,
        ]);

        Permission::create([
            'name' => 'building_block_list',
            'guard_name' => 'web',
            'label' => '楼座列表',
            'group_id' => 2,
        ]);

        Permission::create([
            'name' => 'add_building_block',
            'guard_name' => 'web',
            'label' => '添加楼座',
            'group_id' => 2,
        ]);

        Permission::create([
            'name' => 'update_building_block',
            'guard_name' => 'web',
            'label' => '修改楼座',
            'group_id' => 2,
        ]);

        Permission::create([
            'name' => 'del_building_block',
            'guard_name' => 'web',
            'label' => '删除楼座',
            'group_id' => 2,
        ]);

        // 客户管理
        Permission::create([
            'name' => 'custom_list',
            'guard_name' => 'web',
            'label' => '客户列表',
            'group_id' => 3,
        ]);

        Permission::create([
            'name' => 'custom_show',
            'guard_name' => 'web',
            'label' => '客户详情',
            'group_id' => 3,
        ]);

        Permission::create([
            'name' => 'add_custom',
            'guard_name' => 'web',
            'label' => '添加客户',
            'group_id' => 3,
        ]);

        Permission::create([
            'name' => 'update_custom',
            'guard_name' => 'web',
            'label' => '修改客户',
            'group_id' => 3,
        ]);

        Permission::create([
            'name' => 'del_custom',
            'guard_name' => 'web',
            'label' => '删除客户',
            'group_id' => 3,
        ]);

        Permission::create([
            'name' => 'custom_add_tracks',
            'guard_name' => 'web',
            'label' => '客户添加跟进',
            'group_id' => 3,
        ]);

        Permission::create([
            'name' => 'matching_house',
            'guard_name' => 'web',
            'label' => '匹配房源',
            'group_id' => 3,
        ]);

        // 区域管理
        Permission::create([
            'name' => 'city_list',
            'guard_name' => 'web',
            'label' => '城市列表',
            'group_id' => 4,
        ]);

        Permission::create([
            'name' => 'add_city',
            'guard_name' => 'web',
            'label' => '添加城市',
            'group_id' => 4,
        ]);

        Permission::create([
            'name' => 'del_city',
            'guard_name' => 'web',
            'label' => '删除城市',
            'group_id' => 4,
        ]);

        Permission::create([
            'name' => 'area_list',
            'guard_name' => 'web',
            'label' => '区域列表',
            'group_id' => 4,
        ]);

        Permission::create([
            'name' => 'add_area',
            'guard_name' => 'web',
            'label' => '添加区域',
            'group_id' => 4,
        ]);

        Permission::create([
            'name' => 'del_area',
            'guard_name' => 'web',
            'label' => '删除区域',
            'group_id' => 4,
        ]);

        Permission::create([
            'name' => 'blocks_list',
            'guard_name' => 'web',
            'label' => '商圈列表',
            'group_id' => 4,
        ]);

        Permission::create([
            'name' => 'add_blocks',
            'guard_name' => 'web',
            'label' => '添加商圈',
            'group_id' => 4,
        ]);

        Permission::create([
            'name' => 'del_blocks',
            'guard_name' => 'web',
            'label' => '删除商圈',
            'group_id' => 4,
        ]);

        // 门店管理
        Permission::create([
            'name' => 'storefronts_list',
            'guard_name' => 'web',
            'label' => '门店列表',
            'group_id' => 5,
        ]);

        Permission::create([
            'name' => 'add_storefronts',
            'guard_name' => 'web',
            'label' => '添加门店',
            'group_id' => 5,
        ]);

        Permission::create([
            'name' => 'update_storefronts',
            'guard_name' => 'web',
            'label' => '修改门店',
            'group_id' => 5,
        ]);

        Permission::create([
            'name' => 'del_storefronts',
            'guard_name' => 'web',
            'label' => '删除门店',
            'group_id' => 5,
        ]);

        // 成员管理
        Permission::create([
            'name' => 'user_list',
            'guard_name' => 'web',
            'label' => '成员列表',
            'group_id' => 6,
        ]);

        Permission::create([
            'name' => 'add_user',
            'guard_name' => 'web',
            'label' => '添加成员',
            'group_id' => 6,
        ]);

        Permission::create([
            'name' => 'update_user',
            'guard_name' => 'web',
            'label' => '修改成员',
            'group_id' => 6,
        ]);

        Permission::create([
            'name' => 'del_user',
            'guard_name' => 'web',
            'label' => '删除成员',
            'group_id' => 6,
        ]);


    }
}
