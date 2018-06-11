<?php

namespace App\Console\Commands;

use App\Models\PermissionGroup;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ManagerAddPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'managerAddPermission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '总经理加入新权限';

    /**
     * AddManager constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 总经理加入新权限
        self::managerAddPermission();
    }

    /**
     * 说明: 总经理加入新权限
     *
     * @author 罗振
     */
    public function managerAddPermission()
    {
        // 加入权限
        Permission::create([
            'name' => 'house_img_auditing',
            'guard_name' => 'web',
            'label' => '房源修改图片审核列表',
            'group_id' => 1,
        ]);

        // 查询总经理
        $role = Role::where([
            'name_cn' => '总经理',
            'name' => 'manager',
            'name_en' => 'manager',
            'guard_name' => 'web',
        ])->first();

        $role->givePermissionTo(['house_img_auditing']);

        // 添加组相关权限
        PermissionGroup::create([
            'id' => 7,
            'group_name' => '组管理',
            'parent_id' => null,
            'stage' => 1,
        ]);

        Permission::create([
            'name' => 'group_list',
            'guard_name' => 'web',
            'label' => '组列表',
            'group_id' => 7,
        ]);

        Permission::create([
            'name' => 'add_group',
            'guard_name' => 'web',
            'label' => '组添加',
            'group_id' => 7,
        ]);

        Permission::create([
            'name' => 'update_group',
            'guard_name' => 'web',
            'label' => '组修',
            'group_id' => 7,
        ]);

        $role = Role::where([
            'name_cn' => '店长',
            'name' => 'shop_owner',
            'name_en' => 'shop_owner',
            'guard_name' => 'web',
        ])->first();

        $role->givePermissionTo(['group_list', 'add_group', 'update_group']);
    }
}
