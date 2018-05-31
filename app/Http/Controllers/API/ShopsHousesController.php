<?php
namespace App\Http\Controllers\API;

use App\Handler\Common;
use App\Http\Requests\API\ShopsHousesRequest;
use App\Models\ShopsHouse;
use App\Repositories\ShopsHousesRepository;
use App\Services\HousesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopsHousesController extends APIBaseController
{
    /**
     * 说明: 商铺房源列表
     *
     * @param Request $request
     * @param ShopsHousesRepository $shopsHousesRepository
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function index(
        Request $request,
        ShopsHousesRepository $shopsHousesRepository
    )
    {
        if (empty(Common::user()->can('house_list'))) {
            return $this->sendError('没有房源列表权限', '403');
        }

        $result = $shopsHousesRepository->shopsHousesList($request->per_page??null, json_decode($request->condition));
        return $this->sendResponse($result,'商铺房源列表获取成功!');
    }

    /**
     * 说明: 商铺房源添加
     *
     * @param ShopsHousesRequest $request
     * @param ShopsHousesRepository $shopsHousesRepository
     * @param HousesService $housesService
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function store(
        ShopsHousesRequest $request,
        ShopsHousesRepository $shopsHousesRepository,
        HousesService $housesService
    )
    {
        if(empty(Common::user()->can('add_house'))) {
            return $this->sendError('没有房源添加权限','403');
        }

        $request->model = '\App\Models\ShopsHouse';
        $houseNumValidate = $housesService->houseNumValidate($request);
        if (empty($houseNumValidate['status'])) {
            return $this->sendError($houseNumValidate['message']);
        }

        $result = $shopsHousesRepository->addShopsHouses($request, $housesService);
        if (!empty($result)) {
            return $this->sendResponse($result,'商铺房源添加成功!');
        }

        return $this->sendError('商铺房源添加失败');
    }

    /**
     * 说明: 商铺房源详情
     *
     * @param ShopsHouse $shopsHouse
     * @param HousesService $housesService
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function show(
        ShopsHouse $shopsHouse,
        HousesService $housesService
    )
    {
        $shopsHouse->makeVisible('owner_info');
        $shopsHouse->allId = $housesService->adoptBuildingBlockGetCity($shopsHouse->building_block_id);

        return $this->sendResponse($shopsHouse, '商铺房源修改之前原始数据!');
    }

    /**
     * 说明: 商铺房源修改之前原始数据
     *
     * @param ShopsHouse $shopsHouse
     * @param HousesService $housesService
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function edit(
        ShopsHouse $shopsHouse,
        HousesService $housesService
    )
    {
        if (empty($shopsHouse->see_power_cn)) return $this->sendError('您不能编辑该房源');
        $shopsHouse->makeVisible('owner_info');
        $shopsHouse->allId = $housesService->adoptBuildingBlockGetCity($shopsHouse->building_block_id);

        return $this->sendResponse($shopsHouse, '商铺房源修改之前原始数据!');
    }

    /**
     * 说明: 商铺房源修改
     *
     * @param ShopsHousesRequest $request
     * @param ShopsHousesRepository $shopsHousesRepository
     * @param ShopsHouse $shopsHouse
     * @param HousesService $housesService
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function update(
        ShopsHousesRequest $request,
        ShopsHousesRepository $shopsHousesRepository,
        ShopsHouse $shopsHouse,
        HousesService $housesService
    )
    {
        if(empty(Common::user()->can('update_house'))) {
            return $this->sendError('没有房源修改权限','403');
        }

        $request->model = '\App\Models\ShopsHouse';
        $houseNumValidate = $housesService->houseNumValidate($request, $shopsHouse);
        if (empty($houseNumValidate['status'])) {
            return $this->sendError($houseNumValidate['message']);
        }

        $result = $shopsHousesRepository->updateShopsHouses($shopsHouse, $request);
        return $this->sendResponse($result,'商铺房源修改成功!');
    }

    /**
     * 说明: 删除商铺房源
     *
     * @param  ShopsHouse $shopsHouse
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @author 罗振
     */
    public function destroy(
        ShopsHouse $shopsHouse
    )
    {
        if(empty(Common::user()->can('del_house'))) {
            return $this->sendError('没有房源删除权限','403');
        }

        $res = $shopsHouse->delete();
        return $this->sendResponse($res, '删除成功');
    }

    /**
     * 说明:修改商铺房源业务状态
     *
     * @param ShopsHousesRepository $shopsHousesRepository
     * @param ShopsHousesRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function updateShopsBusinessState
    (
        ShopsHousesRepository $shopsHousesRepository,
        ShopsHousesRequest $request
    )
    {
        if(empty(Common::user()->can('update_business_state'))) {
            return $this->sendError('没有修改住宅房源业务状态权限','403');
        }

        $res = $shopsHousesRepository->updateState($request);
        return $this->sendResponse($res, '商铺房源业务状态修改成功');
    }

    /**
     * 说明:获取我的商铺房源类表
     *
     * @param Request $request
     * @param ShopsHousesRepository $shopsHousesRepository
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function myShopsHousesList(
        Request $request,
        ShopsHousesRepository $shopsHousesRepository
    )
    {
        $user_id = Common::user()->id;
        $result = $shopsHousesRepository->shopsHousesList($request->per_page??null, json_decode($request->condition), $user_id);
        return $this->sendResponse($result,'我的商铺房源列表获取成功!');
    }
}