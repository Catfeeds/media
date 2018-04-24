<?php

namespace App\Http\Controllers\API;

use App\Handler\Common;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends APIBaseController
{
    /**
     * 说明：所有城市
     *
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function index()
    {
        if (empty(Common::user()->can('city_list'))) {
            return $this->sendError('无城市列表权限','403');
        }

        $cities = City::all();
        return $this->sendResponse($cities, '获取成功');
    }

    /**
     * 说明：添加城市
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function store(Request $request)
    {
        if (empty(Common::user()->can('add_city'))) {
            return $this->sendError('无添加城市权限','403');
        }

        $res = City::where('name', $request->name)->first();
        if (!empty($res)) return $this->sendError(405, '城市名重复');
        $res = City::create([
            'name' => $request->name
        ]);
        return $this->sendResponse($res, '添加成功');
    }

    /**
     * 说明：所有城市下拉数据
     *
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function citiesSelect()
    {


        $cities = City::all();
        $res = array();
        foreach ($cities as $v) {
            $item = array(
                'value' => $v->id,
                'label' => $v->name
            );
            $res[] = $item;
        }
        return $this->sendResponse($res, '获取成功');
    }

    /**
     * 说明：删除城市
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function destroy($id)
    {
        if (empty(Common::user()->can('del_city'))) {
            return $this->sendError('无删除城市权限','403');
        }

        $res = City::find($id)->delete();
        return $this->sendResponse($res, '删除成功');
    }
}
