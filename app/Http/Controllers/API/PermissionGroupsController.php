<?php
namespace App\Http\Controllers\API;

use App\Http\Requests\API\PermissionGroupsRequest;
use App\Repositories\PermissionGroupsRepository;

class PermissionGroupsController extends APIBaseController
{
    public function index
    (
        PermissionGroupsRequest $request,
        PermissionGroupsRepository $repository
    )
    {
        $res = $repository->permissionGroupsList($request);
        return $this->sendResponse($res,'权限组列表成功');
    }

    public function store(
        PermissionGroupsRequest $request,
        PermissionGroupsRepository $repository
    )
    {
        $res = $repository->addPermissionGroups($request);
        return $this->sendResponse($res,'添加权限组成功');
    }
}