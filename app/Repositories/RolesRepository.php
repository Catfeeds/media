<?php

namespace App\Repositories;

use \Spatie\Permission\Models\Role;

class RolesRepository extends BaseRepository
{

    private $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    /**
     * 说明:角色列表
     *
     * @param $request
     * @return mixed
     * @author 刘坤涛
     */
    public function rolesList($request)
    {
        return $this->model->paginate(10);

    }

    /**
     * 说明:添加角色和该角色权限
     *
     * @param $request
     * @return bool
     * @author 刘坤涛
     */
    public function addRoles($request)
    {
        \DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
                'name_en' => $request->name_en,
                'name_cn' => $request->name_cn,
                'guard_name' => 'web'
            ]);
            if (empty($role)) {
                throw new \Exception('角色信息添加失败');
            }
            $role->givePermissionTo($request->permission);
            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('添加'.$request->name_cn.'角色失败'.$e->getMessage());
            return false;
        }
    }

    /**
     * 说明:更新角色和该角色权限
     *
     * @param $role
     * @param $request
     * @return bool
     * @author 刘坤涛
     */
    public function updateRoles($role, $request)
    {
        \DB::beginTransaction();
        try {
            $role->name = $request->name;
            $role->name_en = $request->name_en;
            $role->name_cn = $request->name_cn;
            $res = $role->save();
            if (empty($res)) {
                throw new \Exception('角色信息更新失败');
            }
            $role->syncPermissions($request->permission);
            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('更新角色'.$request->name_cn.'失败'.$e->getMessage());
            return false;
        }
    }
}