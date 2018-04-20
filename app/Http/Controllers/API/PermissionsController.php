<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\PermissionsRequest;
use App\Models\Permission;
use App\Repositories\PermissionsRepository;
use Illuminate\Http\Request;

class PermissionsController extends APIBaseController
{

    /**
     * 说明:权限列表
     *
     * @param PermissionsRepository $permissionsRepository
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
	public function index
    (
        PermissionsRepository $permissionsRepository,
        Request $request
    )
	{
        $res = $permissionsRepository->permissionsList($request);
        return $this->sendResponse($res,'权限列表获取成功');
	}

    /**
     * 说明:添加权限
     *
     * @param PermissionsRequest $request
     * @param PermissionsRepository $permissionsRepository
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function store
    (
        PermissionsRequest $request,
        PermissionsRepository $permissionsRepository
    )
    {
        $res = $permissionsRepository->addPermissions($request);
        return $this->sendResponse($res,'权限信息添加成功');

    }

    /**
     * 说明:权限修改之前原始数据
     *
     * @param Permission $permission
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function edit(Permission $permission)
    {
        return $this->sendResponse($permission,'权限修改之前原始数据');
    }

    /**
     * 说明:权限修改
     *
     * @param Permission $permission
     * @param PermissionsRequest $request
     * @param PermissionsRepository $permissionsRepository
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function update
    (
        Permission $permission,
        PermissionsRequest $request,
        PermissionsRepository $permissionsRepository
    )
    {
        $res = $permissionsRepository->updatePermissions($permission, $request);
        return $this->sendResponse($res,'更新成功');
    }

    /**
     * 说明: 删除权限
     *
     * @param Permission $permission
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @author 刘坤涛
     */
    public function destroy(Permission $permission)
    {
        $res = $permission->delete();
        return $this->sendResponse($res,'删除成功');
    }
}

