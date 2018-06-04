<?php

namespace App\Http\Controllers\API;

use App\Services\HomePagesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomePagesController extends APIBaseController
{
    private $id;

    public function __construct()
    {
//        $this->id= Auth::guard('api')->user()->id;
        $this->id= 4;
    }

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

        $res = $service->getData($request->time, $this->id);
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
        $res = $service->waitTrackHouse($this->id);
        return $this->sendResponse($res, '待跟进房源数据获取成功');
    }

    


}
