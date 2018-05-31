<?php
namespace App\Http\Controllers\API;

use App\Handler\Common;
use App\Http\Requests\API\OfficeBuildingHousesRequest;
use App\Models\OfficeBuildingHouse;
use App\Repositories\OfficeBuildingHousesRepository;
use App\Services\HousesService;
use Illuminate\Http\Request;

class OfficeBuildingHousesController extends APIBaseController
{
    /**
     * 说明: 写字楼房源列表
     *
     * @param Request $request
     * @param OfficeBuildingHousesRepository $officeBuildingHousesRepository
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function index(
        Request $request,
        OfficeBuildingHousesRepository $officeBuildingHousesRepository
    )
    {
        // 判断用户权限
        if (empty(Common::user()->can('house_list'))) {
            return $this->sendError('没有房源列表权限', '403');
        }

        $result = $officeBuildingHousesRepository->officeBuildingHousesList($request->per_page??null, json_decode($request->condition));
        return $this->sendResponse($result, '写字楼房源列表获取成功');
    }

    /**
     * 说明: 写字楼房源添加
     *
     * @param OfficeBuildingHousesRequest $request
     * @param OfficeBuildingHousesRepository $officeBuildingHousesRepository
     * @param HousesService $housesService
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function store(
        OfficeBuildingHousesRequest $request,
        OfficeBuildingHousesRepository $officeBuildingHousesRepository,
        HousesService $housesService
    )
    {
        if(empty(Common::user()->can('add_house'))) {
            return $this->sendError('没有房源添加权限','403');
        }

        $request->model = '\App\Models\OfficeBuildingHouse';
        $houseNumValidate = $housesService->houseNumValidate($request);
        if (empty($houseNumValidate['status'])) {
            return $this->sendError($houseNumValidate['message']);
        }

        $result = $officeBuildingHousesRepository->addOfficeBuildingHouses($request, $housesService);
        if (!empty($result)) {
            return $this->sendResponse($result, '写字楼房源添加成功');
        }
        return $this->sendError('写字楼添加失败');
    }

    /**
     * 说明: 写字楼房源详情
     *
     * @param OfficeBuildingHouse $officeBuildingHouse
     * @param HousesService $housesService
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function show(
        OfficeBuildingHouse $officeBuildingHouse,
        HousesService $housesService
    )
    {
        $officeBuildingHouse->makeVisible('owner_info');
        $officeBuildingHouse->allId = $housesService->adoptBuildingBlockGetCity($officeBuildingHouse->building_block_id);

        return $this->sendResponse($officeBuildingHouse, '写字楼修改之前原始数据!');
    }

    /**
     * 说明: 写字楼修改之前原始数据
     *
     * @param OfficeBuildingHouse $officeBuildingHouse
     * @param HousesService $housesService
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function edit(
        OfficeBuildingHouse $officeBuildingHouse,
        HousesService $housesService
    )
    {
        // 本人或上级
        if (empty($officeBuildingHouse->see_power_cn)) return $this->sendError('您不能编辑该房源');
        $officeBuildingHouse->makeVisible('owner_info');
        $officeBuildingHouse->allId = $housesService->adoptBuildingBlockGetCity($officeBuildingHouse->building_block_id);

        return $this->sendResponse($officeBuildingHouse, '写字楼修改之前原始数据!');
    }

    /**
     * 说明: 修改写字楼房源
     *
     * @param OfficeBuildingHousesRequest $request
     * @param OfficeBuildingHousesRepository $officeBuildingHousesRepository
     * @param OfficeBuildingHouse $officeBuildingHouse
     * @param HousesService $housesService
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function update(
        OfficeBuildingHousesRequest $request,
        OfficeBuildingHousesRepository $officeBuildingHousesRepository,
        OfficeBuildingHouse $officeBuildingHouse,
        HousesService $housesService
    )
    {
        if(empty(Common::user()->can('update_house'))) {
            return $this->sendError('没有房源修改权限','403');
        }

        $request->model = '\App\Models\OfficeBuildingHouse';
        $houseNumValidate = $housesService->houseNumValidate($request, $officeBuildingHouse);
        if (empty($houseNumValidate['status'])) {
            return $this->sendError($houseNumValidate['message']);
        }

        $result = $officeBuildingHousesRepository->updateOfficeBuildingHouses($officeBuildingHouse, $request);
        return $this->sendResponse($result, '写字楼房源修改成功');
    }

    /**
     * 说明: 删除写字楼房源
     *
     * @param OfficeBuildingHouse $officeBuildingHouse
     * @param HousesService $housesService
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function destroy(
        OfficeBuildingHouse $officeBuildingHouse,
        HousesService $housesService
    )
    {
        if(empty(Common::user()->can('del_house'))) {
            return $this->sendError('没有房源删除权限','403');
        }

        if (empty($res = $housesService->delHouse($officeBuildingHouse))) return $this->sendError('写字楼房源删除失败');
        return $this->sendResponse($res, '删除成功');
    }

    /**
     * 说明: 修改写字楼房源业务状态
     *
     * @param OfficeBuildingHousesRepository $buildingHousesRepository
     * @param OfficeBuildingHousesRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function updateOfficeBusinessState
    (
        OfficeBuildingHousesRepository $buildingHousesRepository,
        OfficeBuildingHousesRequest $request
    )
    {
        if(empty(Common::user()->can('update_business_state'))) {
            return $this->sendError('没有修改住宅房源业务状态权限','403');
        }

        $res = $buildingHousesRepository->updateState($request);
        return $this->sendResponse($res, '写字楼房源业务状态修改成功');
    }

    /**
     * 说明: 获取我的写字楼房源类表
     *
     * @param Request $request
     * @param OfficeBuildingHousesRepository $officeBuildingHousesRepository
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function myOfficeBuildingHousesList
    (
        Request $request,
        OfficeBuildingHousesRepository $officeBuildingHousesRepository
    )
    {
        $user_id = Common::user()->id;
        $result = $officeBuildingHousesRepository->officeBuildingHousesList($request->per_page??null, json_decode($request->condition), $user_id);
        return $this->sendResponse($result, '获取我的写字楼房源列表成功');
    }

    /**
     * 说明: 上线房源
     *
     * @param OfficeBuildingHousesRequest $request
     * @param OfficeBuildingHousesRepository $repository
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function updateShelf(
        OfficeBuildingHousesRequest $request,
        OfficeBuildingHousesRepository $repository
    )
    {
        $res = $repository->updateShelf($request);
        return $this->sendResponse($res, '上线成功');
    }
}