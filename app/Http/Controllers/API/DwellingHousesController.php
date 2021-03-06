<?php
namespace App\Http\Controllers\API;

use App\Handler\Common;
use App\Http\Requests\API\DwellingHousesRequest;
use App\Models\DwellingHouse;
use App\Repositories\DwellingHousesRepository;
use App\Services\HousesService;
use Illuminate\Http\Request;

class DwellingHousesController extends APIBaseController
{
    /**
     * 说明: 住宅房源列表
     *
     * @param Request $request
     * @param DwellingHousesRepository $dwellingHousesRepository
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function index(
        Request $request,
        DwellingHousesRepository $dwellingHousesRepository
    )
    {
        // 判断用户权限
        if (empty(Common::user()->can('house_list'))) {
            return $this->sendError('没有房源列表权限', '403');
        }
        $result = $dwellingHousesRepository->dwellingHousesList($request->per_page??null, json_decode($request->condition));
        return $this->sendResponse($result,'住宅写字楼列表获取成功');
    }

    /**
     * 说明: 住宅房源添加
     *
     * @param DwellingHousesRequest $request
     * @param DwellingHousesRepository $dwellingHousesRepository
     * @param HousesService $housesService
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function store(
        DwellingHousesRequest $request,
        DwellingHousesRepository $dwellingHousesRepository,
        HousesService $housesService
    )
    {
        if(empty(Common::user()->can('add_house'))) {
            return $this->sendError('没有房源添加权限','403');
        }

        $request->model = '\App\Models\DwellingHouse';
        $houseNumValidate = $housesService->houseNumValidate($request);
        if (empty($houseNumValidate['status'])) {
            return $this->sendError($houseNumValidate['message']);
        }

        if (!empty($result = $dwellingHousesRepository->addDwellingHouses($request, $housesService))) {
            return $this->sendResponse($result,'添加成功');
        }

        return $this->sendError('添加失败');
    }

    /**
     * 说明: 住宅房源详情
     *
     * @param DwellingHouse $dwellingHouse
     * @param HousesService $housesService
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function show
    (
        DwellingHouse $dwellingHouse,
        HousesService $housesService
    )
    {
        $dwellingHouse->makeVisible('owner_info');
        $dwellingHouse->allId = $housesService->adoptBuildingBlockGetCity($dwellingHouse->building_block_id);
        return $this->sendResponse($dwellingHouse, '修改之前原始数据返回成功!');
    }

    /**
     * 说明: 住宅房源修改之前数据
     *
     * @param DwellingHouse $dwellingHouse
     * @param HousesService $housesService
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function edit
    (
        DwellingHouse $dwellingHouse,
        HousesService $housesService
    )
    {
        if (empty($dwellingHouse->see_power_cn)) return $this->sendError('您不能编辑该房源');
        $dwellingHouse->makeVisible('owner_info');
        $dwellingHouse->allId = $housesService->adoptBuildingBlockGetCity($dwellingHouse->building_block_id);
        return $this->sendResponse($dwellingHouse, '修改之前原始数据返回成功!');
    }

    /**
     * 说明: 住宅房源修改
     *
     * @param DwellingHousesRequest $request
     * @param DwellingHousesRepository $dwellingHousesRepository
     * @param DwellingHouse $dwellingHouse
     * @param HousesService $housesService
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function update(
        DwellingHousesRequest $request,
        DwellingHousesRepository $dwellingHousesRepository,
        DwellingHouse $dwellingHouse,
        HousesService $housesService
    )
    {
        if(empty(Common::user()->can('update_house'))) {
            return $this->sendError('没有房源修改权限','403');
        }

        $request->model = '\App\Models\DwellingHouse';
        $houseNumValidate = $housesService->houseNumValidate($request, $dwellingHouse);
        if (empty($houseNumValidate['status'])) {
            return $this->sendError($houseNumValidate['message']);
        }

        $result = $dwellingHousesRepository->updateDwellingHouses($dwellingHouse, $request);
        return $this->sendResponse($result,'修改成功');
    }

    /**
     * 说明: 删除住宅房源
     *
     * @param DwellingHouse $dwellingHouse
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @author 罗振
     */
    public function destroy(
        DwellingHouse $dwellingHouse
    )
    {
        if(empty(Common::user()->can('del_house'))) {
            return $this->sendError('没有房源删除权限','403');
        }

        $res = $dwellingHouse->delete();
        return $this->sendResponse($res, '删除成功');
    }

    /**
     * 说明: 修改住宅房源业务状态
     *
     * @param DwellingHousesRepository $dwellingHousesRepository
     * @param DwellingHousesRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function updateDwellingBusinessState
    (
        DwellingHousesRepository $dwellingHousesRepository,
        DwellingHousesRequest $request
    )
    {
        if(empty(Common::user()->can('update_business_state'))) {
            return $this->sendError('没有修改住宅房源业务状态权限','403');
        }

        $res = $dwellingHousesRepository->updateState($request);
        return $this->sendResponse($res,'住宅房源业务状态修改成功');
    }

    /**
     * 说明:获取我的住宅房源列表
     *
     * @param Request $request
     * @param DwellingHousesRepository $dwellingHousesRepository
     * @param HousesService $housesService
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function myDwellingHousesList
    (
        Request $request,
        DwellingHousesRepository $dwellingHousesRepository,
        HousesService $housesService
    )
    {
        $housesService->houseNumValidate($request);
        // 判断用户权限.
        $user_id = Common::user()->id;
        $result = $dwellingHousesRepository->dwellingHousesList($request->per_page??null, json_decode($request->condition), $user_id);
        return $this->sendResponse($result,'我的住宅房源列表获取成功');
    }



}