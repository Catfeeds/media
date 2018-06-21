<?php

namespace App\Http\Controllers\API;

use App\Handler\Common;
use App\Http\Requests\API\BuildingBlockRequest;
use App\Models\Area;
use App\Models\Building;
use App\Models\BuildingBlock;
use App\Models\City;
use App\Repositories\BuildingBlockRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuildingBlockController extends APIBaseController
{
    /**
     * 说明 拿到楼盘下的所有落座
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function index(Request $request)
    {
        $building_id = $request->building_id;
        // 拿到楼座下面的id
        $buildingBlocks = BuildingBlock::where('building_id', $building_id)->get();
        return $this->sendResponse($buildingBlocks, '获取成功');
    }

    /**
     * 说明：楼座分页列表
     *
     * @param Request $request
     * @param BuildingBlockRepository $repository
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function allBlocks(Request $request, BuildingBlockRepository $repository)
    {
        if (empty(Common::user()->can('building_block_list'))) {
            return $this->sendError('无楼座列表权限','403');
        }

        $res = $repository->getList($request->per_page, $request);
        return $this->sendResponse($res, '获取成功');
    }

    /**
     * 说明：修改某个楼座的名称
     *
     * @param BuildingBlockRequest $request
     * @param BuildingBlock $buildingBlock
     * @param BuildingBlockRepository $repository
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function changeNameUnit
    (
        BuildingBlockRequest $request,
        BuildingBlock $buildingBlock,
        BuildingBlockRepository $repository
    )
    {
        $role = Auth::guard('api')->user()->can('update_building_block');
        if (empty($role)) return $this->sendError('无修改楼座权限','403');

        if (!empty($request->name) && $request->name != $buildingBlock->name && BuildingBlock::where(['building_id' => $request->building_id, 'name' => $request->name, 'name_unit' => $request->name_unit, 'unit' => $request->unit, 'unit_unit' => $request->unit_unit])->first()) return $this->sendError('同一楼盘下楼座名称不能重复');

        $res = $repository->changeNameUnit($buildingBlock, $request);
        return $this->sendResponse($res, '修改成功');
    }

    /**
     * 说明：添加楼座（名称、楼盘）
     *
     * @param BuildingBlockRequest $request
     * @param BuildingBlockRepository $repository
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function addNameUnit(BuildingBlockRequest $request, BuildingBlockRepository $repository)
    {
        if (empty(Common::user()->can('add_building_block'))) return $this->sendError('无添加楼座权限','403');

        $temp = BuildingBlock::where(['building_id' => $request->building_id, 'name' => $request->name, 'name_unit' => $request->name_unit, 'unit' => $request->unit, 'unit_unit' => $request->unit_unit])->first();
        if ($temp) {
            return $this->sendError('同一楼盘下楼座名称不能重复');
        }

        $res = $repository->addNameUnit($request);
        return $this->sendResponse($res, '添加成功');
    }

    /**
     * 说明：单条楼座信息
     *
     * @param BuildingBlock $buildingBlock
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function show(BuildingBlock $buildingBlock)
    {
        return $this->sendResponse($buildingBlock, '获取成功');
    }

    /**
     * 说明: 删除楼座
     *
     * @param BuildingBlock $buildingBlock
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @author jacklin
     */
    public function destroy(BuildingBlock $buildingBlock)
    {
        if (empty(Common::user()->can('del_building_block'))) {
            return $this->sendError('无删除楼座权限','403');
        }

        $count = BuildingBlock::where('building_id', $buildingBlock->building_id)->get()->count();
        if ($count <= 1) return $this->sendError('该楼盘仅剩一个楼座，不能删除');

        // 如果有 写字楼、住宅、商铺 不允许删除
        if (!empty($buildingBlock->shop->count() || !empty($buildingBlock->dWelling->count() || !empty($buildingBlock->office->count()))))
            return $this->sendError('该楼下有房源，不允许删除');

        $res = $buildingBlock->delete();
        return $this->sendResponse($res, '删除成功');
    }

    /**
     * 说明：补充楼座信息
     *
     * @param BuildingBlock $buildingBlock
     * @param Request $request
     * @param BuildingBlockRepository $repository
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function addBlockInfo
    (
        BuildingBlock $buildingBlock,
        Request $request,  //TODO 补充验证
        BuildingBlockRepository $repository
    )
    {
        $res = $repository->addBlockInfo($buildingBlock, $request);
        return $this->sendResponse($res, '修改成功');
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
                $buildings = Building::where('area_id', $area->id)->get();
                $building_box = array();
                foreach ($buildings as $building) {
                    $buildingBlocks = BuildingBlock::where('building_id', $building->id)->get();
                    $buildingBlocks = $buildingBlocks->sortBy('name')->values();
                    $buildingBlockBox = array();
                    foreach ($buildingBlocks as $buildingBlock) {
                        $item = array(
                            'value' => $buildingBlock->id,
                            'label' => $buildingBlock->block_info,
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
}
