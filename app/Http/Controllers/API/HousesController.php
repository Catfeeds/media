<?php
namespace App\Http\Controllers\API;

use App\Http\Requests\API\HousesRequest;
use App\Services\HousesService;

class HousesController extends APIBaseController
{

    /**
     * 说明:获取业主信息
     *
     * @param HousesRequest $request
     * @param HousesService $housesService
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function getOwnerInfo
    (
        HousesRequest $request,
        HousesService $housesService
    )
    {
        // 拿到房子
        $house = $housesService->getHouse($request);
        // 看房子是不是自己的
        if (empty($house) || empty($house->see_power_cn)) return $this->sendError('房源异常');
        // 拿业主信息
        $ownerInfo = $house->owner_info;
        // 查看记录
        $viewRecord = $housesService->getViewRecord($house, $request->per_page);
        return $this->sendResponse( ['owner_info' => $ownerInfo, 'view_record' => $viewRecord],'业主信息,查看记录获取成功');
    }
}