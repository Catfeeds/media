<?php

namespace App\Http\Controllers\API;

use App\Models\Area;
use App\Models\Block;
use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlockController extends APIBaseController
{
    public function index(Request $request)
    {
        $res = Block::paginate(10);
        return $this->sendResponse($res, '获取成功');
    }

    /**
     * 说明：商圈添加
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function store(Request $request)
    {
        $res = Block::create([
            'name' => $request->name,
            'area_id' => $request->area_id
        ]);
        return $this->sendResponse($res, '添加成功');
    }

    /**
     * 说明：某个区域下的商圈下拉数据
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function blocksSelect(Request $request)
    {
        $area_id = $request->area_id;
        if (empty($area_id)) return $this->sendError(405, '参数错误');
        $blocks = Block::where('area_id', $area_id)->get();
        $blockBox = array();
        foreach ($blocks as $v) {
            $item = array(
                'value' => $v->id,
                'label' => $v->name
            );
            $blockBox[] = $item;
        }
        return $this->sendResponse($blockBox, '获取成功');
    }

    /**
     * 说明：所有的商圈下拉数据
     *
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function cityBlocks()
    {
        $cities = City::all();
        $city_box = array();
        foreach ($cities as $index => $city) {
            // 循环城市 将区域的
            $areas = Area::where('city_id', $city->id)->get();
            $area_box = array();
            foreach ($areas as $area) {
                $streets = Block::where('area_id', $area->id)->get();
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
     * 说明：删除商圈
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function destroy($id)
    {
        $res = Block::find($id)->delete();
        return $this->sendResponse($res, '删除成功');
    }
}
