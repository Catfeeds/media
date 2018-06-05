<?php

namespace App\Http\Controllers\API;

use App\Services\HomePagesService;
use Illuminate\Http\Request;

class HomePagesController extends APIBaseController
{
    /**
     * 说明: 后台首页数据
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
    public function officeStatistic
    (
        HomePagesService $service,
        Request $request
    )
    {
        $res = $service->officeStatistic($request->class);
        return $this->sendResponse($res, '写字楼统计数据获取成功');
    }

    /**
     * 说明: 客户统计数据
     *
     * @param HomePagesService $service
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function customerStatistic
    (
        HomePagesService $service,
        Request $request
    )
    {
        $res = $service->customerStatistic($request->class);
        return $this->sendResponse($res, '客户统计数据获取成功');
    }
    




}
