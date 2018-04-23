<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\BuildingRequest;
use App\Models\Area;
use App\Models\Building;
use App\Models\BuildingBlock;
use App\Models\City;
use App\Models\Street;
use App\Repositories\BuildingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuildingController extends APIBaseController
{

    /**
     * 说明：楼盘分页列表
     *
     * @param Request $request
     * @param BuildingRepository $repository
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function index(
        Request $request,
        BuildingRepository $repository
    )
    {
        //判断用户权限
        $role = Auth::guard('api')->user()->can('building_list');
        if (empty($role)) {
            return $this->sendError('没有楼盘列表权限','403');
        }

        $res = $repository->getList($request->per_page, json_decode($request->condition));
        return $this->sendResponse($res, '获取成功');
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
        $role= Auth::guard('api')->user()->can('add_building');
        if (empty($role)) {
            return $this->sendError('无添加楼盘权限','403');
        }

        // 楼栋信息不能为空
        if (empty($request->building_block)) return $this->sendError('楼栋信息不能为空');
        // 楼盘名不允许重复
        $res = Building::where(['name' => $request->name, 'area_id' => $request->area_id])->first();
        if (!empty($res)) return $this->sendError('该城区下已有此楼盘，请勿重复添加');

        $res = $repository->add($request);
        if (empty($res)) return $this->sendError('添加失败');
        return $this->sendResponse($res, 200);
    }


    /**
     * 说明：修改楼盘数据
     *
     * @param BuildingRequest $request
     * @param Building $building
     * @param BuildingRepository $repository
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function update(BuildingRequest $request, Building $building, BuildingRepository $repository)
    {
        $role = Auth::guard('api')->user()->can('update_building');
        if (empty($role)) {
            return $this->sendError('无修改楼盘权限','403');
        }

        $res = $repository->updateData($building, $request);
        return $this->sendResponse($res, '修改成功');
    }

    /**
     * 说明：单个楼盘数据
     *
     * @param Building $building
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function show(Building $building)
    {
        $building->building_blocks = $building->buildingBlocks;
        return $this->sendResponse($building, '获取成功');
    }

    /**
     * 说明: 楼盘删除
     *
     * @param Building $building
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @author jacklin
     */
    public function destroy(Building $building)
    {
        $role = Auth::guard('api')->user()->can('del_building');
        if (empty($role)) {
            return $this->sendError('无删除楼盘权限','403');
        }

        $res = $building->delete();
        return $this->sendResponse($res, '删除成功');
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
                $buildings = Building::where('area_id', $area->id)->get();
                $building_box = array();
                foreach ($buildings as $building) {
                    $item = array(
                        'value' => $building->id,
                        'label' => $building->name,
                    );
                    $building_box[] = $item;
                }
                $area_item = array(
                    'value' => $area->id,
                    'label' => $area->name,
                    'children' => $building_box
                );
                $area_box[] = $area_item;
            }
            $city_item = array(
                'value' => $city->id,
                'label' => $city->name,
                'children' => $area_box
            );
            $city_box[] = $city_item; // 所有城市
        }
        return $this->sendResponse($city_box, '获取成功');
    }

    /**
     * 说明：获取某个区下的所有楼盘的下拉数据
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function areaBuildings(Request $request)
    {
        $buildings = Building::where('area_id', $request->area_id)->get();

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
     * 说明: 楼盘搜索下拉
     *
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function buildingSearchSelect()
    {
        $cities = City::all();
        $city_box = array();
        foreach ($cities as $index => $city) {
            // 循环城市 将区域的
            $areas = Area::where('city_id', $city->id)->get();
            $area_box = array();
            foreach ($areas as $area) {
                // 获取楼盘
                $buildings = $area->Building->flatten();
                $building_box = array();
                foreach ($buildings as $building) {
                    $item = array(
                        'value' => $building->id,
                        'label' => $building->name,
                    );
                    $building_box[] = $item;
                }
                $item = array(
                    'value' => $area->id,
                    'label' => $area->name,
                    'children' => $building_box
                );
                $area_box[] = $item;
            }
            $city_item = array(
                'value' => $city->id,
                'label' => $city->name,
                'children' => $area_box
            );
            $city_box[] = $city_item; // 所有城市
        }
        return $this->sendResponse($city_box, '获取成功');
    }

}
