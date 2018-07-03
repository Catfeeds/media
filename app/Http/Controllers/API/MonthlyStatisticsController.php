<?php

namespace App\Http\Controllers\API;

use App\Services\MonthlyStatisticsService;
use Illuminate\Http\Request;

class MonthlyStatisticsController extends APIBaseController
{
    // 统计来源为58,安居客,赶集的房源,客户数量
    public function index(
        Request $request,
        MonthlyStatisticsService $service
    )
    {
        // 获取指定年份每月开始及结束时间
        $times = $service->getTime($request->year);

        // 获取房源,客户来源数量
        $res = $service->getSourceCount($times);

        return $this->sendResponse($res,'获取房源,客户统计数据成功');
    }
}
