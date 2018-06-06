<?php

namespace App\Console\Commands;

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
//        Permission::create([
//            'name' => 'house_img_auditing',
//            'guard_name' => 'web',
//            'label' => '房源修改图片审核列表',
//            'group_id' => 1,
//        ]);

        Permission::create([
            'name' => 'house_state_list',
            'guard_name' => 'web',
            'label' => '房源状态列表',
            'group_id' => 1,
        ]);

        // 查询总经理
        $role = Role::where([
            'name_cn' => '总经理',
            'name' => 'manager',
            'name_en' => 'manager',
            'guard_name' => 'web',
        ])->first();

        $role->givePermissionTo(['house_state_list']);
    }
}
