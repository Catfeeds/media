<?php
namespace App\Http\Controllers\API;

use App\Http\Requests\API\HousesRequest;
use App\Services\HousesService;
use Illuminate\Http\Request;

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
        // 判断当前登录用户是否有权限查看该房源业主信息和查看记录
        if (empty($house) || empty($house->see_power_cn)) return $this->sendError('房源异常');
        // 获取业主信息
        $ownerInfo = $house->owner_info;
        // 获取查看记录
        $viewRecord = $housesService->getViewRecord($house, $request->per_page);
        return $this->sendResponse( ['owner_info' => $ownerInfo, 'view_record' => $viewRecord],'业主信息,查看记录获取成功');
    }

    public function houseImgUpdateView(Request $request)
    {
        // 判断是否本人操作（header 的 token，和 当前用户的token）
        // 没有house 返回错误
        // 失效时间判断 2分钟
        return view('agency.house.img_house_update');
    }

    public function houseImgUpdate()
    {
        // 判断是否本人操作
        // 没有house 返回错误
        // 更新house的图片
    }
}