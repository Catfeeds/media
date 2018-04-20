<?php
namespace App\Http\Controllers\API;

use App\Http\Requests\API\OfficeBuildingHousesRequest;
use App\Models\BuildingBlock;
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
        $result = $officeBuildingHousesRepository->addOfficeBuildingHouses($request, $housesService);
        return $this->sendResponse($result, '写字楼房源添加成功');
    }

    /**
     * 说明: 写字楼修改之前原始数据
     *
     * @param OfficeBuildingHouse $officeBuildingHouse
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function edit(
        OfficeBuildingHouse $officeBuildingHouse,
        HousesService $housesService
    )
    {
        $officeBuildingHouse->allId = $housesService->adoptBuildingBlockGetCity($officeBuildingHouse->building_block_id);

        return $this->sendResponse($officeBuildingHouse, '写字楼修改之前原始数据!');
    }

    /**
     * 说明: 修改写字楼房源
     *
     * @param OfficeBuildingHousesRequest $request
     * @param OfficeBuildingHousesRepository $officeBuildingHousesRepository
     * @param OfficeBuildingHouse $officeBuildingHouse
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function update(
        OfficeBuildingHousesRequest $request,
        OfficeBuildingHousesRepository $officeBuildingHousesRepository,
        OfficeBuildingHouse $officeBuildingHouse
    )
    {
        if (!empty($result = $officeBuildingHousesRepository->updateOfficeBuildingHouses($officeBuildingHouse, $request))) {
            return $this->sendResponse($result, '写字楼房源修改成功');
        }

        return  $this->sendError('写字楼房源修改失败');
    }

    /**
     * 说明: 删除写字楼房源
     *
     * @param  OfficeBuildingHouse $officeBuildingHouse
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @author 罗振
     */
    public function destroy(
        OfficeBuildingHouse $officeBuildingHouse
    )
    {
        $res = $officeBuildingHouse->delete();
        return $this->sendResponse($res, '删除成功');
    }
}