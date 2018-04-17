<?php

namespace App\Repositories;

use App\Models\BuildingBlock;

class BuildingBlockRepository extends BaseRepository
{

    private $model;

    public function __construct(BuildingBlock $model)
    {
        $this->model = $model;
    }

    public function add($request)
    {
        $this->model->create([
            '' => '',
            '' => '',
            '' => ''
        ]);
    }

    /**
     * 说明：修改单个楼座的单元和楼座名称
     *
     * @param $buildingBlock
     * @param $request
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
     * 说明：添加楼座单元和楼座名称
     *
     * @param $request
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
}