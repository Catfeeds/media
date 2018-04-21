<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\RolesRequest;
use App\Models\Role;
use App\Repositories\RolesRepository;
use Illuminate\Http\Request;

class RolesController extends APIBaseController
{

    public function index
    (Request $request,
     RolesRepository $rolesRepository
    )
    {
        $res = $rolesRepository->rolesList($request);
        return $this->sendResponse($res, '角色列表获取成功');
    }

    public function store
    (
        RolesRepository $rolesRepository,
        RolesRequest $request
    )
    {
        $res = $rolesRepository->addRoles($request);
        return $this->sendResponse($res, '角色信息添加成功');
    }


    public function edit(Role $role)
    {
        return $this->sendResponse($role, '角色修改之前原始数据');
    }


    public function update
    (
        Role $role,
        RolesRequest $request,
        RolesRepository $rolesRepository
    )
    {
        $res = $rolesRepository->updateRoles($role, $request);
        return $this->sendResponse($res, '更新成功');
    }


    public function destroy(Role $role)
    {
        $res = $role->delete();
        return $this->sendResponse($res, '角色删除成功');
    }
}
