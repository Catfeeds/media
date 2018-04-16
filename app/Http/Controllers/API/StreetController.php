<?php

namespace App\Http\Controllers\API;

use App\Models\Area;
use App\Models\City;
use App\Models\Street;
use Illuminate\Http\Request;

class StreetController extends APIBaseController
{
    /**
     * 说明：街道分页数据
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function index(Request $request)
    {
        $res = Street::paginate(10);
        return $this->sendResponse($res, '获取成功');
    }

    /**
     * 说明：街道添加
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function store(Request $request)
    {
        $res = Street::where(['name' => $request->name, 'area_id' => $request->area_id])->first();
        if (!empty($res)) return $this->sendError(405, '街道名重复');

        $res = Street::create([
            'name' => $request->name,
            'area_id' => $request->area_id
        ]);
        return $this->sendResponse($res, '添加成功');
    }

    /**
     * 说明：所有街道下拉数据
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function streetsSelect(Request $request)
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
                    $item = array(
                        'value' => $street->id,
                        'label' => $street->name,
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

    /**
     * 说明：删除街道
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function destroy($id)
    {
        $res = Street::find($id)->delete();
        return $this->sendResponse($res, '删除成功');
    }
}
