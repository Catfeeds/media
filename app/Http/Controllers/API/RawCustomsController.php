<?php

namespace App\Http\Controllers\API;


use App\Http\Requests\API\RawCustomsRequest;
use App\Models\RawCustom;
use App\Repositories\RawCustomsRepository;
use App\Services\HousesService;
use App\Services\RawCustomsService;

class RawCustomsController extends APIBaseController
{
    public function index
    (
        RawCustomsRepository $repository,
        RawCustomsRequest $request,
        RawCustomsService $service
    )
    {
        dr(123);
        $res= $repository->getList($request, $service);
        return $this->sendResponse($res,'工单列表获取成功');
    }

    //添加工单
    public function store
    (
        RawCustomsRepository $repository,
        RawCustomsRequest $request,
        HousesService $service,
        RawCustomsService $rawCustomsService
    )
    {
        $res = $repository->addRawCustom($request, $service);
        //通过店长id查手机号,curl查微信openid,发送微信消息
        $openid = $rawCustomsService->getOpenid($request->shopkeeper_id);
        if ($res && $openid) $rawCustomsService->send($openid,$res->name,$res->tel);
        //发送微信消息
        if (!$res) return $this->sendError('客户录入失败');
        return $this->sendResponse($res, '客户录入成功');
    }

    //获取所有店长信息
    public function getShopkeeper(RawCustomsService $service)
    {
        $res = $service->getShopkeeper();
        return $this->sendResponse($res,'店长信息获取成功');
    }

    //获取店长下面的业务员
    public function getStaff
    (
        RawCustomsService $service,
        RawCustomsRequest $request
    )
    {
        $id = $service->getId($request->tel);
        $res = $service->getStaff($id);
        return $this->sendResponse($res, '业务员信息获取成功');
    }

    //店长分配工单
    public function distribution
    (
        RawCustomsRequest $request,
        RawCustomsRepository $repository,
        RawCustomsService $service
    )
    {
        $res = $repository->distribution($request);
        //获取用户的openid
        $openid = $service->getOpenid($request->staff_id);
        //获取该记录的客户名称和电话
        $item = RawCustom::where('id', $request->id)->first();
        if ($res) $service->send($openid,$item->name,$item->tel, true);
        return $this->sendResponse($res, '工单分配成功');
    }

    //店员确定工单
    public function determine
    (
        RawCustomsRequest $request,
        RawCustomsRepository $repository
    )
    {
        $res = $repository->determine($request);
        return $this->sendResponse($res, '工单确认成功');
    }

    //手机端店长处理工单界面
    public function shopkeeperList
    (
        RawCustomsRequest $request,
        RawCustomsRepository $repository,
        RawCustomsService $service
    )
    {
        //通过电话查询id
        $id = $service->getId($request->tel);
        $res = $repository->shopkeeperList($request, $service, $id);
        return $this->sendResponse($res, '店长处理页面获取成功');
    }

    //业务员能处理页面
    public function staffList
    (
        RawCustomsRequest $request,
        RawCustomsRepository $repository,
        RawCustomsService $service
    )
    {
        $id = $service->getId($request->tel);
        $res = $repository->staffList($request, $service, $id);
        return $this->sendResponse($res, '业务员处理页面获取成功');
    }
    
}
