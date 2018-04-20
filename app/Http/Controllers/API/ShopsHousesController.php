<?php
namespace App\Http\Controllers\API;

use App\Http\Requests\API\ShopsHousesRequest;
use App\Models\ShopsHouse;
use App\Repositories\ShopsHousesRepository;
use App\Services\HousesService;
use Illuminate\Http\Request;

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
        $result = $shopsHousesRepository->shopsHousesList($request);
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
        $result = $shopsHousesRepository->addShopsHouses($request, $housesService);
        return $this->sendResponse($result,'商铺房源添加成功!');
    }

    /**
     * 说明: 商铺房源修改之前原始数据
     *
     * @param ShopsHouse $shopsHouse
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function edit(
        ShopsHouse $shopsHouse
    )
    {
        return $this->sendResponse($shopsHouse, '商铺房源修改之前原始数据!');
    }

    /**
     * 说明: 商铺房源修改
     *
     * @param ShopsHousesRequest $request
     * @param ShopsHousesRepository $shopsHousesRepository
     * @param ShopsHouse $shopsHouse
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function update(
        ShopsHousesRequest $request,
        ShopsHousesRepository $shopsHousesRepository,
        ShopsHouse $shopsHouse
    )
    {
        if (!empty($result = $shopsHousesRepository->updateShopsHouses($shopsHouse, $request))) {
            return $this->sendResponse($result,'商铺房源修改成功!');
        }

        return $this->sendError('商铺房源修改失败');
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
        $res = $shopsHouse->delete();
        return $this->sendResponse($res, '删除成功');
    }
}