<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAddPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roleAddPermission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '店长和业务员添加权限';

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
        // 店长和业务员添加权限
        self::roleAddPermission();
    }

    /**
     * 说明: 写字楼,商铺房源加入单价总价
     *
     * @author 罗振
     */
    public function roleAddPermission()
    {
        $roles = Role::all();
        foreach ($roles as $role) {
            $model = Permission::where('guard_name','web');
            if ($role->id == 3) {
                $role->syncPermissions($model->where('group_id', '!=', 5)->pluck('name')->toArray());
            } elseif ($role->id == 4) {
                $salesManPermissions = $model->whereNotIn('group_id', [5, 6])->pluck('name')->toArray();
                $salesManPermissions = array_diff($salesManPermissions, ['del_house', 'del_building', 'del_building_block', 'del_city', 'del_area', 'del_blocks', 'del_storefronts', 'del_user', 'del_custom']);
                $role->syncPermissions($salesManPermissions);
            }
        }
    }
}
