<?php

namespace App\Repositories;

use App\Models\MediaPermissionGroup;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class RolesRepository extends Model
{
    public function roleList($request)
    {
        return Role::where(['guard_name' => 'web'])->paginate($request->per_page??10);
    }

    public function addRole(
        $request
    )
    {
        \DB::beginTransaction();
        try {
            // 添加角色表
            $role = Role::create([
                'name' => $request->name_en,
                'name_cn' => $request->name_cn,
                'name_en' => $request->name_en,
                'guard_name' => 'web',
            ]);
            // 添加关联表
            $role->givePermissionTo($request->permissions);
            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('添加角色失败：' . $e->getFile() . $e->getLine() . $e->getMessage());
            return false;
        }
    }

    public function updateRole(
        $request,
        Role $role
    )
    {
        \DB::beginTransaction();
        try {
            $role->name = $request->name_en;
            $role->name_cn = $request->name_cn;
            $role->name_en = $request->name_en;
            if (!$role->save()) throw new \Exception('修改角色信息失败!');

            // 修改关联表
            $role->syncPermissions($request->permissions);

            curl(config('setting.agency_host').'/api/run_command', 'get',true);

            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('修改角色失败：' . $e->getFile() . $e->getLine() . $e->getMessage());
            return false;
        }
    }

    public function getAllPermissions()
    {
        // 获取所有权限组
        $groups = MediaPermissionGroup::where(['stage' => 1])->get();

        $data = array();
        foreach ($groups as $k => $v) {
            $data['group'.$v->id]['title'] = $v->group_name;
            $permissions = $v->permission;

            $allPermissions = array();  // 所有权限信息
            $allPermissionId = array(); // 所有权限id
            foreach ($permissions as $key => $val) {
                $allPermissions[$key]['key'] = $val->name;
                $allPermissions[$key]['label'] = $val->label;
                $allPermissions[$key]['disable'] = true;
                $allPermissionId[] = $val->name;
            }

            $data['group'.$v->id]['permission'] = $allPermissions;
            $data['group'.$v->id]['allPermissionId'] = $allPermissionId;
        }

        return $data;
    }
}