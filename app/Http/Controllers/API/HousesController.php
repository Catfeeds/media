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
        $res = $housesService->getOwnerInfo($request);
        return $this->sendResponse($res,'业主信息获取成功');
    }

}