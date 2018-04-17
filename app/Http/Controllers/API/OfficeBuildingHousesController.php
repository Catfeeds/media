<?php
namespace App\Http\Controllers\API;



use App\Http\Requests\API\OfficeBuildingHousesRequest;
use App\Repositories\OfficeBuildingHousesRepository;
use App\Services\HousesService;

class OfficeBuildingHousesController extends APIBaseController
{
    public function index(
        OfficeBuildingHousesRepository $officeBuildingHousesRepository
    )
    {
        $result = $officeBuildingHousesRepository->officeBuildingHousesList();
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
}