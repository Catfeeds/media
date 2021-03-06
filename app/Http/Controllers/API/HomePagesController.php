<?php

namespace App\Http\Controllers\API;

use App\Services\HomePagesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class HomePagesController extends APIBaseController
{
    /**
     * 说明: 后台首页新增数据
     *
     * @param HomePagesService $service
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function index
    (
        HomePagesService $service,
        Request $request
    )
    {

        $res = $service->getData($request->time);
        return $this->sendResponse($res, '获取成功');
    }

    /**
     * 说明: 后台首页待跟进房源数据
     *
     * @param HomePagesService $service
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function waitTrackHouse
    (
        HomePagesService $service
    )
    {
        $res = $service->waitTrackHouse();
        return $this->sendResponse($res, '待跟进房源数据获取成功');
    }

    /**
     * 说明: 后台首页待跟进客户数据
     *
     * @param HomePagesService $service
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function waitTrackCustomer(HomePagesService $service)
    {
        $res = $service->waitTrackCustomer();
        return $this->sendResponse($res, '待跟进客户数据获取成功');
    }

    /**
     * 说明: 写字楼统计数据
     *
     * @param HomePagesService $service
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function statisticData
    (
        HomePagesService $service,
        Request $request
    )
    {
        $res = $service->statisticData($request->model, $request->class);
        return $this->sendResponse($res, '统计数据获取成功');
    }

    /**
     * 说明: 获取房源或客源环比数据
     *
     * @param HomePagesService $service
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function getRingThanData
    (
        HomePagesService $service,
        Request $request
    )
    {
        $res = $service->getRingThanData($request->model);
        return $this->sendResponse($res, '环比数据获取成功');
    }

    /**
     * 说明: 获取房源、客源图表数据
     *
     * @param HomePagesService $service
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛\
     */
    public function getChartData
    (
        HomePagesService $service,
        Request $request
    )
    {
        $res = $service->getChartData($request->model, $request->time);
        return $this->sendResponse($res, '图表数据获取成功');
    }

    /**
     * 说明: 根据登录人等级查看对应业务员数据
     *
     * @param HomePagesService $service
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function getSalesmanData
    (
        HomePagesService $service,
        Request $request
    )
    {
        $res = $service->getSalesmanData($request);
        return $this->sendResponse($res, '获取成功');
    }

    // 清除权限缓存
    public function runCommand()
    {
        app()['cache']->forget('spatie.permission.cache');
    }
}
