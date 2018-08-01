<?php
namespace App\Http\Controllers\API;

use App\Http\Requests\API\RolesRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Repositories\RolesRepository;

class RolesController extends APIBaseController
{
    public function index(
        RolesRequest $request,
        RolesRepository $repository
    )
    {
        $res = $repository->roleList($request);
        return $this->sendResponse($res,'获取角色列表成功');
    }

    public function store(
        RolesRequest $request,
        RolesRepository $repository
    )
    {

        if (!is_array($request->permissions)) $request->permissions = json_decode($request->permissions);
        $item = Permission::all()->pluck('name')->toArray();
        foreach ($request->permissions as $value) {
            if (!in_array($value, $item)) return $this->sendError('权限必须存在');
        }
        $res = $repository->addRole($request);
        if (empty($res)) return $this->sendError('角色添加失败');
        return $this->sendResponse($res,'角色添加成功');
    }

    public function edit(
        Role $role
    )
    {
        // 角色所有权限
        $role->oldPermissions = $role->permissions()->pluck('name')->toArray();
        return $this->sendResponse($role,'获取角色原始数据成功');
    }

    public function update(
        Role $role,
        RolesRequest $request,
        RolesRepository $repository
    )
    {
        if (!is_array($request->permissions)) $request->permissions = json_decode($request->permissions);
        $res = $repository->updateRole($request, $role);
        if (empty($res)) return $this->sendError('角色修改失败');
        return $this->sendResponse($res,'角色修改成功');
    }

    // 获取所有权限
    public function getAllPermissions(
        RolesRepository $repository
    )
    {
        $res = $repository->getAllPermissions();
        return $this->sendResponse($res,'获取所有权限数据成功');
    }

}