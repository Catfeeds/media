<?php
namespace App\Http\Controllers\API;



use App\Http\Requests\API\OfficeBuildingHousesRequest;
use App\Repositories\OfficeBuildingHousesRepository;

class OfficeBuildingHousesController extends APIBaseController
{
    public function index()
    {
        return '写字楼房源控制器';
    }

    /**
     * 说明: 写字楼房源添加
     *
     * @param OfficeBuildingHousesRequest $request
     * @param OfficeBuildingHousesRepository $officeBuildingHousesRepository
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function store(
        OfficeBuildingHousesRequest $request,
        OfficeBuildingHousesRepository $officeBuildingHousesRepository
    )
    {
        return $this->sendResponse($officeBuildingHousesRepository->addOfficeBuildingHouses($request), '写字楼房源添加成功');
    }
}