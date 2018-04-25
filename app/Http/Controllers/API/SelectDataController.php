<?php

namespace App\Http\Controllers\API;


use App\Handler\Common;
use App\Models\Area;
use App\Models\Building;
use App\Models\BuildingBlock;
use App\Models\Custom;
use App\Models\DwellingHouse;
use App\Models\OfficeBuildingHouse;
use App\Models\ShopsHouse;
use App\Models\Storefront;
use App\User;
use Illuminate\Http\Request;

class SelectDataController extends APIBaseController
{

    /**
     * 说明：武汉的所有楼盘
     *
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function areaBuildings()
    {
        $city = 1;
        $areas = Area::where('city_id', $city)->get()->pluck('id')->toArray();
        $buildings = Building::whereIn('area_id', $areas)->get();

        $res = array();
        foreach ($buildings as $building) {
            $item = array(
                'value' => $building->id,
                'label' => $building->name
            );
            $res[] = $item;
        }

        return $this->sendResponse($res, '获取成功');
    }

    /**
     * 说明：某个楼盘下的所有楼栋
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function buildingBlocks(Request $request)
    {
        $buildingId = $request->building_id;
        $buildingBlocks = BuildingBlock::where('building_id', $buildingId)->get();
        $res = array();
        foreach ($buildingBlocks as $buildingBlock) {
            $item = array(
                'value' => $buildingBlock->id,
                'label' => $buildingBlock->name . $buildingBlock->name_unit . $buildingBlock->unit . $buildingBlock->unit_unit);
            $res[] = $item;
        }
        return $this->sendResponse($res, '获取成功');
    }


    /**
     * 说明：某楼座下的所有房号
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function blockHouses(Request $request)
    {
        $buildingBlockId = $request->building_block_id;
        // 住宅 写字楼 商铺
        $buildingBlock = BuildingBlock::find($buildingBlockId);
        if (empty($buildingBlock)) return $this->sendResponse([], '楼盘不存在');

        switch ($buildingBlock->building->type) {
            case 1:
                $model = DwellingHouse::make();
                break;
            case 2:
                $model = OfficeBuildingHouse::make();
                break;
            case 3:
                $model = ShopsHouse::make();
                break;
        }

        // 用户信息
        $user = Common::user();

        // 查询所有房源数据
        $allHouse = $model->pluck('storefront', 'id');

        $storePublid = array();
        $private = array();
        if ($user->level == 1) {
            // 1. 总经理

            $allPublic = $model->all()->toArray();

        } elseif($user->level == 2) {
            // 2. 区域经理

            // 公盘
            $allPublic = $model->where([
                'guardian' => null,
                'storefront' => null
            ])->get()->toArray();

            // 店内公盘
            $storefrontId = Storefront::where('area_manager_id', $user->id)->pluck('id')->toArray();

            $allHouseId = array();
            foreach ($allHouse as $k => $v) {
                if (!empty($v)) {
                    foreach ($v as $val) {
                        if (in_array($val, $storefrontId)) {
                            $allHouseId[] = $k;
                        }
                    }
                }
            }
            // 去重
            $storePublid = array_unique($allHouseId);

        } elseif ($user->level == 3) {
            // 3. 店长

            // 公盘
            $allPublic = $model->where([
                'guardian' => null,
                'storefront' => null
            ])->get()->toArray();

            // 店内公盘
            $storefrontId = Storefront::where('user_id', $user->id)->pluck('id')->toArray();
            if (!empty($storefront)) {
                // 获取门店下所有店员id
                $users = User::where('ascription_store', $storefront->id)->pluck('id')->toArray();

                if (!empty($users)) {
                    // 私盘
                    $private = $model->whereIn('guardian', $users)->get()->toArray();
                }

                // 店内公盘
                $storefrontHouse = array();
                foreach ($allHouse as $k => $v) {
                    if (!empty($v)) {
                        foreach ($v as $val) {
                            if (in_array($val, $storefrontId)) {
                                $storefrontHouse[] = $k;
                            }
                        }
                    }
                }
                $storePublid = array_unique($storefrontHouse);
            }
        } else {
            // 4. 业务员

            // 公盘
            $allPublic = $model->where([
                'guardian' => null,
                'storefront' => null
            ])->get()->toArray();

            // 店内公盘
            $publicHouseId = array();
            foreach ($allHouse as $k => $v) {
                if (!empty($v)) {
                    foreach ($v as $val) {
                        if ($val == $user->ascription_store) {
                            $publicHouseId[] = $k;
                        }
                    }
                }
            }
            $storePublid = array_unique($publicHouseId);

            // 私盘
            $private = $model->where([
                'guardian' => $user->id,
            ])->get()->toArray();
        }

        $houses = array_merge($allPublic, $private, $storePublid);

        $res = array();
        foreach ($houses as $house) {
            $item = array(
                'value' => $house['id'],
                'label' => $house['house_number']
            );
            $res[] = $item;
        }
        return $this->sendResponse($res, '获取成功');
    }

    /**
     * 说明：所有客户数据
     *
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function selectCustoms()
    {
        // 用户信息
        $user = Common::user();

        $storePublid = array();
        $private = array();
        if ($user->level == 1) {
            // 1. 总经理

            // 公盘
            $allPublic = Custom::all()->toArray();
        } elseif ($user->level == 2) {
            // 2. 区域经理

            // 公盘
            $allPublic = Custom::where([
                'guardian' => null,
                'storefront' => null
            ])->get()->toArray();

            // 店内公盘
            $storefrontId = Storefront::where('area_manager_id', $user->id)->pluck('id')->toArray();
            if (!empty($storefrontId)) {
                $storePublid = Custom::whereIn('storefront', $storefrontId)->where('guardian', null)->get()->toArray();
            }

        } elseif ($user->level == 3) {
            // 3. 店长

            // 公盘
            $allPublic = Custom::where([
                'guardian' => null,
                'storefront' => null
            ])->get()->toArray();

            // 店内公盘
            $storefront = Storefront::where('user_id', $user->id)->first();
            if (!empty($storefront)) {
                // 获取门店下所有店员id
                $users = User::where('ascription_store', $storefront->id)->pluck('id')->toArray();
                if (!empty($users)) {
                    // 私盘
                    $private = Custom::whereIn('guardian', $users)->get()->toArray();
                }

                // 店内公盘
                $storePublid = Custom::where('storefront', $storefront->id)->get()->toArray();
            }
        } else {
            // 4. 业务员

            // 公盘
            $allPublic = Custom::where([
                'guardian' => null,
                'storefront' => null
            ])->get()->toArray();

            // 公司公盘
            if (!empty($user->ascription_store)) {
                $storePublid = Custom::where([
                    'guardian' => null,
                    'storefront' => $user->ascription_store
                ])->get()->toArray();
            }

            // 私盘
            $private = Custom::where([
                'guardian' => $user->id,
            ])->get()->toArray();
        }

        $customs = array_merge($allPublic, $storePublid, $private);

        $res = array();
        foreach ($customs as $custom) {
            $item = array(
                'value' => $custom['id'],
                'label' => $custom['name'] . $custom['tel']
            );
            $res[] = $item;
        }
        return $this->sendResponse($res, '获取成功');
    }
}
