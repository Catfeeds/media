<?php

namespace App\Http\Controllers\API;


use App\Http\Requests\API\RawCustomsRequest;
use App\Repositories\RawCustomsRepository;
use App\Services\HousesService;
use App\Services\RawCustomsService;
use App\Services\UsersService;

class RawCustomsController extends APIBaseController
{
    public function index
    (
        RawCustomsRepository $repository,
        RawCustomsRequest $request,
        RawCustomsService $service
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
        $url= 'http://www.baidu.com';
        $data = array(
            'first' => array('您好,您有新的客户', '#555555'),
            'keyword1' => array('贾456456464','#336699') ,
            'keyword2' => array('110','#ff0000'),
            'keyword3' => array('写字楼租赁','#888888'),
            'keyword4' => array(date('Y-m-d H:i:s',time()),'#888888'),
            'remark'   => array('感谢您的使用','#5599ff')
        );
        $arr['url'] = $url;
        $arr['data'] = $data;
        $arr['openid'] = 'oPRyPwyGIy7pf2Ei-xG1lNjHdmo4';
        dd(getData('http://msg_manager.jacklin.club/weSend', 'post', json_encode($arr)));
        return $this->sendResponse($res, '客户录入成功');
    }

    //获取所有店长信息
    public function getShopkeeper(RawCustomsService $service)
    {
        $res = $service->getShopkeeper();
        return $this->sendResponse($res,'店长信息获取成功');
    }

    //获取店长下面的业务员
    public function getStaff(RawCustomsService $service)
    {
        $res = $service->getStaff();
        return $this->sendResponse($res, '业务员信息获取成功');
    }

    //店长分配工单
    public function distribution
    (
        RawCustomsRequest $request,
        RawCustomsRepository $repository
    )
    {
        $res = $repository->distribution($request);
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
        $res = $repository->shopkeeperList($request, $service);
        return $this->sendResponse($res, '店长处理页面获取成功');
    }
    
}
