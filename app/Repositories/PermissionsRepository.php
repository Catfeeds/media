<?php

namespace App\Repositories;

use App\Models\Permission;

class PermissionsRepository extends BaseRepository
{

    private $model;

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    /**
     * 说明:权限列表
     *
     * @param $request
     * @return mixed
     * @author 刘坤涛
     */
    public function permissionsList($request)
    {
        return $this->model->paginate(10);
    }

    /**
     * 说明:添加权限
     *
     * @param $request
     * @return $this|\Illuminate\Database\Eloquent\Model
     * @author 刘坤涛
     */
    public function addPermissions($request)
    {
        return $this->model->create([
            'name' => $request->name,
            'label' => $request->label,
            'group_id' => $request->group_id
        ]);
    }

    /**
     * 说明:权限修改
     *
     * @param $permission
     * @param $request
     * @return bool
     * @author 刘坤涛
     */
    public function updatePermissions($permission, $request)
    {
        $permission->name = $request->name;
        $permission->label = $request->label;
        $permission->group_id = $request->group_id;

        if (!$permission->save()) {
            return false;
        }
        return true;
    }
}