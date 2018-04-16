<?php

namespace App\Repositories;

use App\Models\Block;
use App\Models\Building;
use App\Models\BuildingBlock;
use Illuminate\Support\Facades\DB;

class BuildingRepository extends Building
{

    private $model;

    public function __construct(Building $model)
    {
        $this->model = $model;
    }

    public function add($request)
    {
        DB::beginTransaction();
        try{
            // 添加楼盘表
            $this->model->create([
                'name' => $request->name,
                'gps' => $request->gps,

                'type' => $request->type,
                'street_id' => $request->street_id,
                'block_id' => $request->block_id,
                'address' => $request->address,

                'developer' => $request->developer,
                'years' => $request->years,
                'acreage' => $request->acreage,
                'building_block_num' => $request->building_block_num,
                'parking_num' => $request->parking_num,
                'parking_fee' => $request->parking_fee,
                'greening_rate' => $request->greening_rate,

                'company' => $request->company?json_encode($request->company):null,
                'album' => $request->album?json_encode($request->album):null
            ]);
            // 循环添加楼座表
            $buildingBlocks = $request->building_block;

            foreach ($buildingBlocks as $buildingBlock) {
                if (empty($buildingBlock->name))
                Block::create([
                    'name' => $buildingBlock->name,
                    'name_unit' => $buildingBlock->name_unit,
                    'unit' => $buildingBlock->unit,
                    'unit_unit' => $buildingBlock->unit_unit,
                ]);
            }
        }
        catch (\Exception $exception){
            DB::rollBack();
        }
        DB::commit();
    }
}