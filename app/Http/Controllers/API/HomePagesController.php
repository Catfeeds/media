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


    public function index
    (
        HomePagesService $service,
        Request $request
    )
    {

        $res = $service->getData($request->time, $this->id);
        return $this->sendResponse($res, '获取成功');
    }

    public function waitTrackHouse
    (
        HomePagesService $service
    )
    {
        $res = $service->waitTrackHouse($this->id);
        return $this->sendResponse($res, '获取待跟进房源数据成功');
    }


}
