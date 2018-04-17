<?php

namespace App\Repositories;

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

    /**
     * 说明：楼盘列表
     *
     * @param array $where
     * @param null $perPage
     * @author jacklin
     */
    public function getList($where = array(), $perPage = null)
    {
        return $this->model->where($where)
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);
    }

    public function add($request)
    {
        DB::beginTransaction();
        try {
            // 添加楼盘表
            $res = $this->model->create([
                'name' => $request->name,
                'gps' => json_encode($request->gps),

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

                'company' => json_encode($request->company),
                'album' => json_encode($request->album)
            ]);
            // 循环添加楼座表
            $buildingBlocks = $request->building_block;

            foreach ($buildingBlocks as $buildingBlock) {
                if (empty($buildingBlock->name))
                    BuildingBlock::create([
                        'building_id' => $res->id,
                        'name' => $buildingBlock['name'],
                        'name_unit' => $buildingBlock['name_unit'],
                        'unit' => $buildingBlock['unit'],
                        'unit_unit' => $buildingBlock['unit_unit'],
                    ]);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }


    /**
     * 说明：更新楼盘信息
     *
     * @param array $buildingBlock
     * @param array $request
     * @return mixed
     * @author jacklin
     */
    public function updateData($buildingBlock, $request)
    {
        $res = $buildingBlock->update([
            'name' => $request->name,
            'gps' => json_encode($request->gps),

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

            'company' => json_encode($request->company),
            'album' => json_encode($request->album)
        ]);
        return $res;
    }
}