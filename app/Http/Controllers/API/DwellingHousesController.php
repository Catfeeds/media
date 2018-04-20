<?php
namespace App\Http\Controllers\API;

use App\Http\Requests\API\DwellingHousesRequest;
use App\Models\BuildingBlock;
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
        $result = $dwellingHousesRepository->dwellingHousesList($request->per_page??null, $request->condition);
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
        if (!empty($result = $dwellingHousesRepository->addDwellingHouses($request, $housesService))) {
            return $this->sendResponse($result,'添加成功');
        }

        return $this->sendError('添加失败');
    }

    /**
     * 说明: 住宅房源修改之前数据
     *
     * @param DwellingHouse $dwellingHouse
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function edit(
        DwellingHouse $dwellingHouse
    )
    {

        dd(BuildingBlock::find(2)->building->street);






        return $this->sendResponse($dwellingHouse, '修改之前原始数据返回成功!');
    }

    /**
     * 说明: 住宅房源修改
     *
     * @param DwellingHousesRequest $request
     * @param DwellingHousesRepository $dwellingHousesRepository
     * @param DwellingHouse $dwellingHouse
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function update(
        DwellingHousesRequest $request,
        DwellingHousesRepository $dwellingHousesRepository,
        DwellingHouse $dwellingHouse
    )
    {
        if (!empty($result = $dwellingHousesRepository->updateDwellingHouses($dwellingHouse, $request))) {
            return $this->sendResponse($result,'修改成功');
        }

        return $this->sendError('修改失败');
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
        $res = $dwellingHouse->delete();
        return $this->sendResponse($res, '删除成功');
    }
}