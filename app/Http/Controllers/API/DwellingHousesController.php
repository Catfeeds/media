<?php
namespace App\Http\Controllers\API;

use App\Http\Requests\API\DwellingHousesRequest;
use App\Repositories\DwellingHousesRepository;
use App\Services\HousesService;

class DwellingHousesController extends APIBaseController
{
    /**
     * 说明: 住宅房源列表
     *
     * @param DwellingHousesRepository $dwellingHousesRepository
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function index(
        DwellingHousesRepository $dwellingHousesRepository
    )
    {
        $result = $dwellingHousesRepository->dwellingHousesList();
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
        $result = $dwellingHousesRepository->addDwellingHouses($request, $housesService);
        return $this->sendResponse($result,'添加成功');
    }
}