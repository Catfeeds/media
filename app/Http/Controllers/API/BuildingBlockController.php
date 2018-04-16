<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\APIBaseController;
use App\Models\Area;
use App\Models\Building;
use App\Models\BuildingBlock;
use App\Models\City;
use App\Models\Street;
use App\Repositories\BuildingBlockRepository;
use Illuminate\Http\Request;

class BuildingBlockController extends APIBaseController
{
    public function index()
    {
        
    }

    public function store(BuildingBlockRepository $repository)
    {
//        $repository->add();
    }

    /**
     * 说明：所有的楼座下拉数据
     *
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function buildingBlocksSelect()
    {
        $cities = City::all();
        $city_box = array();
        foreach ($cities as $index => $city) {
            // 循环城市 将区域的
            $areas = Area::where('city_id', $city->id)->get();
            $area_box = array();
            foreach ($areas as $area) {
                $streets = Street::where('area_id', $area->id)->get();
                $street_box = array();
                foreach ($streets as $street) {
                    $buildings = Building::where('street_id', $street->id)->get();
                    $building_box = array();
                    foreach ($buildings as $building) {
                        $buildingBlocks = BuildingBlock::all();
                        $buildingBlockBox = array();
                        foreach ($buildingBlocks as $buildingBlock) {
                            $item = array(
                                'value' => $buildingBlock->id,
                                'label' => $buildingBlock->name,
                            );
                            $buildingBlockBox[] = $item;
                        }
                        $item = array(
                            'value' => $building->id,
                            'label' => $building->name,
                            'children' => $buildingBlockBox
                        );
                        $building_box[] = $item;
                    }
                    $item = array(
                        'value' => $street->id,
                        'label' => $street->name,
                        'children' => $building_box
                    );
                    $street_box[] = $item;
                }
                $item = array(
                    'value' => $area->id,
                    'label' => $area->name,
                    'children' => $street_box
                );
                $area_box[] = $item; // 城市下的区
            }
            $city_item = array(
                'value' => $city->name,
                'label' => $city->name,
                'children' => $area_box
            );
            $city_box[] = $city_item; // 所有城市
        }
        return $this->sendResponse($city_box, '获取成功');
    }
}
