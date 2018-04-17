<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\BuildingRequest;
use App\Models\Area;
use App\Models\Building;
use App\Models\City;
use App\Models\Street;
use App\Repositories\BuildingRepository;

class BuildingController extends APIBaseController
{

    public function index(BuildingRequest $request, BuildingRepository $repository)
    {

    }

    /**
     * 说明：楼盘添加
     *
     * @param BuildingRequest $request
     * @param BuildingRepository $repository
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function store(BuildingRequest $request, BuildingRepository $repository)
    {
        // 楼栋信息不能为空
        if (empty($request->building_block)) return $this->sendError(405, '楼栋信息不能为空');
        // 楼盘名不允许重复
        $res = Building::where(['name' => $request->name, 'street_id' => $request->street_id])->first();
        if (!empty($res)) return $this->sendError(405, '楼盘名不能重复');
        $res = $repository->add($request);

        if (empty($res)) return $this->sendError(405, '添加失败');
        return $this->sendResponse($res, 200);
    }


    /**
     * 说明：所有楼盘下拉数据
     *
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function buildingSelect()
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
                        $item = array(
                            'value' => $building->id,
                            'label' => $building->name,
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
