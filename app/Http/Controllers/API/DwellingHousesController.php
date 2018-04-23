<?php
namespace App\Http\Controllers\API;

use App\Http\Requests\API\DwellingHousesRequest;
use App\Models\DwellingHouse;
use App\Models\Storefront;
use App\Repositories\DwellingHousesRepository;
use App\Services\HousesService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Monolog\Handler\IFTTTHandler;

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
        // 判断用户权限
        $user = Auth::guard('api')->user();
        if (empty($user->can('house_list'))) {
            return $this->sendError('没有房源列表权限', '403');
        }


        // 总经理
        if ($user->level == 1) {
            // 查询所有
        } elseif($user->level == 2) {
            $storefront = Storefront::where('area_manager_id', $user->id)->all()->pluck('id')->toArray();

            $allHouseId = DwellingHouse::whereIn('storefront', $storefront)->pluck('id')->toArray();
        } elseif($user->level == 3) {
            $dwellingHouseId = array();

            // 查询门店的所有房源
            // 判断是否属于店长
            $storefront = Storefront::where('user_id', $user->id)->first();
            if (!empty($storefront)) {
                // 获取门店下所有店员id
                $users = User::where('ascription_store', $storefront->id)->pluck('id')->toArray();

                if (!empty($users)) {
                    // 获取房源id
                    $dwellingHouseId = DwellingHouse::whereIn('guardian', $users)->pluck('id')->toArray();
                }

                // 店内公盘
                $storefrontHouse = DwellingHouse::where('storefront', $storefront->id)->pluck('id')->toArray();

                $allHouseId = array_merge($dwellingHouseId, $storefrontHouse);
            }

        } elseif($user->level == 4) {

        } else {

    }


        $result = $dwellingHousesRepository->dwellingHousesList($request->per_page??null, json_decode($request->condition));
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
        $role = Auth::guard('api')->user()->can('add_house');
        if(empty($role)) {
            return $this->sendError('没有房源添加权限','403');
        }

        if (!empty($result = $dwellingHousesRepository->addDwellingHouses($request, $housesService))) {
            return $this->sendResponse($result,'添加成功');
        }

        return $this->sendError('添加失败');
    }

    /**
     * 说明: 住宅房源修改之前数据
     *
     * @param DwellingHouse $dwellingHouse
     * @param HousesService $housesService
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function edit(
        DwellingHouse $dwellingHouse,
        HousesService $housesService
    )
    {
        $dwellingHouse->allId = $housesService->adoptBuildingBlockGetCity($dwellingHouse->building_block_id);

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
        $role = Auth::guard('api')->user()->can('update_house');
        if(empty($role)) {
            return $this->sendError('没有房源修改权限','403');
        }

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
        $role = Auth::guard('api')->user()->can('del_house');
        if(empty($role)) {
            return $this->sendError('没有房源删除权限','403');
        }

        $res = $dwellingHouse->delete();
        return $this->sendResponse($res, '删除成功');
    }

    /**
     * 说明: 修改住宅房源业务状态
     *
     * @param DwellingHousesRepository $dwellingHousesRepository
     * @param DwellingHousesRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function updateDwellingBusinessState
    (
        DwellingHousesRepository $dwellingHousesRepository,
        DwellingHousesRequest $request
    )
    {
        $role = Auth::guard('api')->user()->can('update_business_state');
        if(empty($role)) {
            return $this->sendError('没有修改住宅房源业务状态权限','403');
        }

        $res = $dwellingHousesRepository->updateState($request);
        return $this->sendResponse($res,'住宅房源业务状态修改成功');
    }
}