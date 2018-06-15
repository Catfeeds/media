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
        $buildingBlocks = $buildingBlocks->sortBy('name')->values();
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

        $houses = $model->where('building_block_id', $buildingBlockId)->get();

        $res = array();
        foreach ($houses as $house) {
            $item = array(
                'value' => $house->id,
                'label' => $house->house_number
            );
            $res[] = $item;
        }
        return $this->sendResponse($res, '获取成功');
    }

    /**
     * 说明: 房源添加跟进用户下拉框
     *
     * @return \Illuminate\Support\Collection|static
     * @author 罗振
     */
    public function selectCustoms()
    {
        $user = Common::user();

        if ($user->level == 1) {
            $result = Custom::all();
        } elseif ($user->level == 2) {
            $storefrontId = Storefront::where('area_manager_id', $user->id)->pluck('id')->toArray();

            if (!empty($storefrontId)) {
                $userId = User::whereIn('ascription_store', $storefrontId)->pluck('id')->toArray();
            }

            // 下级私盘
            if (!empty($userId)) {
                $result = Custom::whereIn('guardian', $userId)->orWhere('guardian', null)->get()->toArray();
            }
        } elseif ($user->level == 3) {
            $storefrontId = Storefront::where('user_id', $user->id)->first()->id;

            if (!empty($storefrontId)) {
                $userId = User::whereIn('ascription_store', $storefrontId)->pluck('id')->toArray();
            }

            if (!empty($userId)) {
                $result = Custom::whereIn('guardian', $userId)->orWhere('guardian', null)->get();
            }
        } else {
            $result = Custom::whereIn('guardian', $user->id)->orWhere('guardian', null)->get();
        }

        return $result->map(function($v) {
            return [
                'label' => $v->name,
                'value' => $v->id
            ];
        });
    }
}
