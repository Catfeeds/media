<?php

namespace App\Http\Controllers\API;

use App\Models\Area;
use App\Models\City;
use Illuminate\Http\Request;

class AreaController extends APIBaseController
{
    /**
     * 说明：某城市下的所有区
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function index(Request $request)
    {
        $city_id = $request->city_id??1;
        $res = Area::where('city_id', $city_id)->get();
        return $this->sendResponse($res, '获取成功');
    }

    /**
     * 说明：区添加
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function store(Request $request)
    {
        $res = Area::where(['name' => $request->name, 'city_id' => $request->city_id])->first();
        if (!empty($res)) return $this->sendError(405, '区名重复');

        $res = Area::create([
            'name' => $request->name,
            'city_id' => $request->city_id
        ]);
        return $this->sendResponse($res, '添加成功');
    }

    /**
     * 说明：所有区的下拉数据
     *
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function areasSelect()
    {
        $cities = City::all();
        $city_box = array();
        foreach ($cities as $index => $city) {
            // 循环城市 将区域的
            $areas = Area::where('city_id', $city->id)->get();
            $area_box = array();
            foreach ($areas as $area) {
                $item = array(
                    'value' => $area->id,
                    'label' => $area->name,
                );
                $area_box[] = $item; // 城市下的区
            }
            $city_item = array(
                'value' => $city->id,
                'label' => $city->name,
                'children' => $area_box,
            );
            $city_box[] = $city_item; // 所有城市
        }
        return $this->sendResponse($city_box, '获取成功');
    }

    /**
     * 说明：删除区
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function destroy($id)
    {
        $res = Area::find($id)->delete();
        return $this->sendResponse($res, '删除成功');
    }
}
