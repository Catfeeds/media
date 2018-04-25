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

        $user = Common::user();

        $storePublid = array();
        $private = array();
        if ($user->level == 1) {
            $allPublic = $model->all()->toArray();
        } elseif($user->level == 2) {
            // 公盘
            $allPublic = $model->where([
                'guardian' => null,
                'storefront' => null
            ])->get()->toArray();

            // 私盘
            $private = $model->where([
                'guardian' => $user->id,
            ])->get()->toArray();

            $storefrontId = Storefront::where('area_manager_id', $user->id)->pluck('id')->toArray();
            if (!empty($storefrontId)) {
                $storePublid = $model->whereIn('storefront', $storefrontId)->where('guardian', null)->get()->toArray();
            }
        } elseif ($user->level == 3) {
            // 公盘
            $allPublic = $model->where([
                'guardian' => null,
                'storefront' => null
            ])->get()->toArray();

            // 私盘
            $private = $model->where([
                'guardian' => $user->id,
            ])->get()->toArray();

            // 店内公盘
            $storefront = Storefront::where('user_id', $user->id)->first();
            if (!empty($storefront)) {
                $storePublid = $model->where('storefront', $storefront->id)->pluck('id')->toArray();
            }
        } else {
            // 公盘
            $allPublic = $model->where([
                'guardian' => null,
                'storefront' => null
            ])->get()->toArray();

            // 店内公盘
            if (!empty($user->ascription_store)) {
                $storePublid = $model->where([
                    'guardian' => null,
                    'storefront' => $user->ascription_store
                ])->get()->toArray();
            }

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

        $user = Common::user();
        // 公盘
        $allPublic = Custom::where([
            'guardian' => null,
            'storefront' => null
        ])->get()->toArray();

        // 公司公盘
        $storePublid = array();
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
