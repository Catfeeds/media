<?php

namespace App\Http\Controllers\API;


use App\Models\Area;
use App\Models\Building;

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
        $buildings = Building::whereIn('id', $areas)->get();

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
}
