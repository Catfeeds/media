<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\BuildingBlockRequest;
use App\Models\Area;
use App\Models\Building;
use App\Models\BuildingBlock;
use App\Models\City;
use App\Models\Street;
use App\Repositories\BuildingBlockRepository;
use Illuminate\Http\Request;

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
        $res = $repository->getList([], $request->per_page);
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
     * 说明：删除楼座
     *
     * @param BuildingBlock $buildingBlock
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function destroy(BuildingBlock $buildingBlock)
    {
        $count = BuildingBlock::where('building_id', $buildingBlock->building_id)->get()->count();
        if ($count <= 1) return $this->sendError('该楼盘仅剩一个楼座，不能删除');

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
                $streets = Street::where('area_id', $area->id)->get();
                $street_box = array();
                foreach ($streets as $street) {
                    $buildings = Building::where('street_id', $street->id)->get();
                    $building_box = array();
                    foreach ($buildings as $building) {
                        $buildingBlocks = BuildingBlock::where('building_id', $building->id)->get();
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
