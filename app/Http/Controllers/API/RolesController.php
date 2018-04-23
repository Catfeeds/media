<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\RolesRequest;
use App\Repositories\RolesRepository;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolesController extends APIBaseController
{
    /**
     * 说明:角色列表
     *
     * @param Request $request
     * @param RolesRepository $rolesRepository
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function index
    (
        Request $request,
        RolesRepository $rolesRepository
    )
    {
        $res = $rolesRepository->rolesList($request);
        return $this->sendResponse($res, '角色列表获取成功');
    }

    /**
     * 说明:添加角色
     *
     * @param RolesRepository $rolesRepository
     * @param RolesRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function store
    (
        RolesRepository $rolesRepository,
        RolesRequest $request
    )
    {
        $res = $rolesRepository->addRoles($request);
        if ($res) {
            return $this->sendResponse($res, '角色信息添加成功');
        }
        return $this->sendError('角色信息添加失败');
    }

    /**
     * 说明:角色修改之前原始数据
     *
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function edit(Role $role)
    {
        // TODO 还有获取角色下所有权限
        return $this->sendResponse($role, '角色修改之前原始数据');
    }

    /**
     * 说明:跟新角色信息
     *
     * @param Role $role
     * @param RolesRequest $request
     * @param RolesRepository $rolesRepository
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function update
    (
        Role $role,
        RolesRequest $request,
        RolesRepository $rolesRepository
    )
    {
        $res = $rolesRepository->updateRoles($role, $request);
        if ($res) {
            return $this->sendResponse($res, '更新成功');
        }
        return $this->sendError('更新失败');
    }

    /**
     * 说明:删除角色
     *
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @author 刘坤涛
     */
    public function destroy(Role $role)
    {
        $res = $role->delete();
        return $this->sendResponse($res, '角色删除成功');
    }
}
