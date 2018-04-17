<?php
namespace App\Http\Controllers\API;


use App\Http\Requests\API\ShopsHousesRequest;
use App\Repositories\ShopsHousesRepository;
use App\Services\HousesService;

class ShopsHousesController extends APIBaseController
{
    public function index()
    {
        return '商铺房源控制器';
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
        $result = $shopsHousesRepository->addShopsHouses($request, $housesService);
        return $this->sendResponse($result,'商铺房源添加成功!');
    }
}