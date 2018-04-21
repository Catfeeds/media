<?php

namespace App\Repositories;

use App\Models\Area;
use App\Models\Building;
use App\Models\BuildingBlock;

class BuildingBlockRepository extends BaseRepository
{

    private $model;

    public function __construct(BuildingBlock $model)
    {
        $this->model = $model;
    }

    /**
     * 说明：获取所有楼座的列表
     *
     * @param array $where
     * @param null $perPage
     * @return mixed
     * @author jacklin
     */
    public function getList(
        $per_page,
        $condition
    )
    {
        $result = $this->model->orderBy('updated_at', 'desc');

        if (!empty($condition->building_id)) {
            $buildingId = array_column(Building::find(1)->buildingBlocks->flatten()->toArray(), 'id');
            $result = $result->where('id', $buildingId);
        } elseif(!empty($condition->area_id)) {
            $buildingId = array_column(Area::find($condition->area_id)->BuildingBlock->flatten()->toArray(), 'id');
            $result = $result->whereIn('id', $buildingId);
        }

        return $result->paginate($per_page??10);
    }

    /**
     * 说明: 修改单个楼座的单元和楼座名称
     *
     * @param $buildingBlock
     * @param $request
     * @return mixed
     * @author jacklin
     */
    public function changeNameUnit($buildingBlock, $request)
    {
        $buildingBlock->name = $request->name;
        $buildingBlock->name_unit = $request->name_unit;
        $buildingBlock->unit = $request->unit;
        $buildingBlock->unit_unit = $request->unit_unit;
        return $buildingBlock->save();
    }

    /**
     * 说明: 添加楼座单元和楼座名称
     *
     * @param $request
     * @return mixed
     * @author jacklin
     */
    public function addNameUnit($request)
    {
        $res = $this->model->create([
            'building_id' => $request->building_id,
            'name' => $request->name,
            'name_unit' => $request->name_unit,
            'unit' => $request->unit,
            'unit_unit' => $request->unit_unit
        ]);
        return $res;
    }

    /**
     * 说明：补充某个楼座的楼座信息
     *
     * @param $buildingBlock
     * @param $request
     * @return mixed
     * @author jacklin
     */
    public function addBlockInfo($buildingBlock, $request)
    {
        $buildingBlock->class = $request->class;
        $buildingBlock->structure = $request->structure;
        $buildingBlock->total_floor = $request->total_floor;
        $buildingBlock->property_company = $request->property_company;
        $buildingBlock->property_fee = $request->property_fee;

        $buildingBlock->heating = $request->heating;
        $buildingBlock->air_conditioner = $request->air_conditioner;

        $buildingBlock->passenger_lift = $request->passenger_lift;
        $buildingBlock->cargo_lift = $request->cargo_lift;
        $buildingBlock->president_lift = $request->president_lift;

        return $buildingBlock->save();
    }
}