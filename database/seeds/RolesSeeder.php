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
        $role= Role::create([
            'name_cn' => '总经理',
            'name' => 'manager',
            'name_en' => 'manager',
            'guard_name' => 'web',
        ]);
        $role->givePermissionTo(Permission::where('guard_name','web')->pluck('name')->toArray());
    }
}
