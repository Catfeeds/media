<?php
namespace App\Http\Controllers\API;

use App\Http\Requests\API\DwellingHousesRequest;
use App\Repositories\DwellingHousesRepository;

class DwellingHousesController extends APIBaseController
{
    public function index()
    {
        return '住宅控制器';
    }

    /**
     * 说明: 住宅房源添加
     *
     * @param DwellingHousesRequest $request
     * @param DwellingHousesRepository $dwellingHousesRepository
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function store(
        DwellingHousesRequest $request,
        DwellingHousesRepository $dwellingHousesRepository
    )
    {
        return $this->sendResponse($dwellingHousesRepository->addDwellingHouses($request),'添加成功');
    }
}