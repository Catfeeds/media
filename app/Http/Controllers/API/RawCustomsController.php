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
        $wechat = app('wechat');
        dd($wechat);
        $url= 'http://www.baidu.com';
        $data = array(
            'first' => array('您好,您有新的客户', '#555555'),
            'keyword1' => array('贾','#336699') ,
            'keyword2' => array('110','#ff0000'),
            'keyword3' => array('写字楼租赁','#888888'),
            'keyword4' => array(date('Y-m-d H:i:s',time()),'#888888'),
            'remark'   => array('感谢您的使用','#5599ff')
        );
        $wechat->notice->to('oPRyPwyGIy7pf2Ei-xG1lNjHdmo4')->uses('9byKW2NiFk4PoLsdZF4WATd-okTuUIwk0nZK0XeYoHE')->andUrl($url)->data($data)->send();
        return $this->sendResponse($res, '客户录入成功');
    }

    public function getShopkeeper(UsersService $service)
    {
        $res = $service->getShopkeeper();
        return $this->sendResponse($res,'店长信息获取成功');
    }
}
