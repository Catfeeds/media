<?php

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = Permission::where('guard_name','web');

        $role= Role::create([
            'name_cn' => '总经理',
            'name' => 'manager',
            'name_en' => 'manager',
            'guard_name' => 'web',
        ]);
        $role->givePermissionTo($model->pluck('name')->toArray());

        // 区域经理
        $areaManager = Role::create([
            'name_cn' => '区域经理',
            'name' => 'area_manager',
            'name_en' => 'area_manager',
            'guard_name' => 'web',
        ]);
        $areaManager->givePermissionTo($model->pluck('name')->toArray());

        // 店长全部权限
        $shopOwnerPermissions = $model->where('group_id', '!=', 5)->pluck('name')->toArray();

        // 业务员所有权限
        $salesManPermissions = $model->whereNotIn('group_id', [5, 6])->pluck('name')->toArray();
        $salesManPermissions = array_diff($salesManPermissions, ['del_house', 'del_building', 'del_building_block', 'del_city', 'del_area', 'del_blocks', 'del_storefronts', 'del_user']);

        // 店长
        $shopOwner = Role::create([
            'name_cn' => '区域经理',
            'name' => 'shop_owner',
            'name_en' => 'shop_owner',
            'guard_name' => 'web',
        ]);
        $shopOwner->givePermissionTo($shopOwnerPermissions);

        // 业务员
        $salesMan = Role::create([
            'name_cn' => '业务员',
            'name' => 'sales_an',
            'name_en' => 'sales_an',
            'guard_name' => 'web',
        ]);
        $salesMan->givePermissionTo($salesManPermissions);
    }
}
