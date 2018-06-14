<?php

namespace App\Http\Controllers\API;


use App\Handler\Common;
use App\Http\Requests\API\RawCustomsRequest;
use App\Repositories\RawCustomsRepository;
use App\Services\HousesService;
use App\Services\UsersService;

class RawCustomsController extends APIBaseController
{
    public function index
    (
        RawCustomsRepository $repository,
        RawCustomsRequest $request,
        UsersService $service
    )
    {
        $res= $repository->getList($request, $service);
        return $this->sendResponse($res,'工单列表获取成功');
    }

    public function store
    (
        RawCustomsRepository $repository,
        RawCustomsRequest $request,
        HousesService $service
    )
    {
        $res = $repository->addRawCustom($request, $service);

        return $this->sendResponse($res, '客户录入成功');
    }

    public function getShopkeeper(UsersService $service)
    {
        $res = $service->getShopkeeper();
        return $this->sendResponse($res,'店长信息获取成功');
    }
}
