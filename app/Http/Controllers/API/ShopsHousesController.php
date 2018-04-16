<?php
namespace App\Http\Controllers\API;


use App\Http\Requests\API\ShopsHousesRequest;
use App\Repositories\ShopsHousesRepository;

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
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function store(
        ShopsHousesRequest $request,
        ShopsHousesRepository $shopsHousesRepository
    )
    {
        $result = $shopsHousesRepository->addShopsHouses($request);
        return $this->sendResponse($result,'商铺房源添加成功!');
    }
}