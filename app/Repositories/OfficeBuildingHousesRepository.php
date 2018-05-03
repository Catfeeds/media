<?php
namespace App\Repositories;

use App\Handler\Common;
use App\Models\Area;
use App\Models\Building;
use App\Models\OfficeBuildingHouse;
use App\Services\HousesService;

class OfficeBuildingHousesRepository extends BaseRepository
{

    private $model;

    public function __construct(OfficeBuildingHouse $model)
    {
        $this->model = $model;
    }

    /**
     * 说明: 写字楼房源列表
     *
     * @param $per_page
     * @param $condition
     * @return mixed
     * @author 罗振
     */
    public function officeBuildingHousesList(
        $per_page,
        $condition
    )
    {
        $result = $this->model;

        if (!empty($condition->region) && !empty($condition->build)) {
            // 楼盘包含的楼座
            $blockId = array_column(Building::find($condition->build)->buildingBlocks->toArray(), 'id');
            $result = $result->whereIn('building_block_id', $blockId);
        } elseif (!empty($condition->region) && empty($condition->build)) {
            // 区域包含的楼座
            $blockId = array_column(Area::find($condition->region)->building_block->flatten()->toArray(), 'id');
            $result = $result->whereIn('building_block_id', $blockId);
        }

        // 最小面积
        if (!empty($condition->min_acreage)) {
            $result = $result->where('constru_acreage', ">=", (int)$condition->min_acreage);
        }
        // 最大面积
        if (!empty($condition->max_acreage)) {
            $result = $result->where('constru_acreage', "<=", (int)$condition->max_acreage);
        }

        // 排序
        if (!empty($condition->order)) {
            $result = $result->orderBy('updated_at', $condition->order);
        }

        return $result->paginate($per_page??10)->makeHidden('owner_info');
    }

    /**
     * 说明: 写字楼房源添加
     *
     * @param $request
     * @param HousesService $housesService
     * @return bool
     * @author 罗振
     */
    public function addOfficeBuildingHouses($request, HousesService $housesService)
    {
        \DB::beginTransaction();
        try {
            $house =  $this->model->create([
                'building_block_id' => $request->building_block_id,
                'house_number' => $request->house_number,
                'owner_info' => $request->owner_info,
                'room' => $request->room,
                'hall' => $request->hall,
                'constru_acreage' => $request->constru_acreage,
                'split' => $request->split,
                'min_acreage' => $request->min_acreage,
                'floor' => $request->floor,
                'station_number' => $request->station_number,
                'office_building_type' => $request->office_building_type,
                'register_company' => $request->register_company,
                'open_bill' => $request->open_bill,
                'renovation' => $request->renovation,
                'orientation' => $request->orientation,
                'support_facilities' => $request->support_facilities??array(),
                'house_description' => $request->house_description,
                'rent_price' => $request->rent_price,
                'rent_price_unit' => $request->rent_price_unit,
                'payment_type' => $request->payment_type,
                'check_in_time' => $request->check_in_time,
                'shortest_lease' => $request->shortest_lease,
                'rent_free' => $request->rent_free,
                'increasing_situation' => $request->increasing_situation,
                'increasing_situation_remark' => $request->increasing_situation_remark,
                'cost_detail' => $request->cost_detail??array(),
                'house_busine_state' => $request->house_busine_state,
                'pay_commission' => $request->pay_commission,
                'pay_commission_unit' => $request->pay_commission_unit,
                'prospecting' => $request->prospecting,
                'source' => $request->source,
                'house_key' => $request->house_key,
                'see_house_time' => $request->see_house_time,
                'see_house_time_remark' => $request->see_house_time_remark,
                'certificate_type' => $request->certificate_type,
                'house_proxy_type' => $request->house_proxy_type,
                'guardian' => Common::user()->id,
                'house_type_img' => $request->house_type_img,
                'indoor_img' => $request->indoor_img,
            ]);
            if (empty($house)) {
                throw new \Exception('写字楼房源添加失败');
            }

            $house->house_identifier = $housesService->setHouseIdentifier('X', $house->id);
            if (empty($house->save())) {
                throw new \Exception('写字楼房源编号添加失败');
            }

            \DB::commit();
            return $house;
        } catch (\Exception $e) {
            \Log::error('写字楼房源添加失败：' . $e->getFile() . $e->getLine() . $e->getMessage());
            return false;
        }
    }

    /**
     * 说明: 修改写字楼
     *
     * @param $officeBuildingHouse
     * @param $request
     * @return bool
     * @author 罗振
     */
    public function updateOfficeBuildingHouses($officeBuildingHouse, $request)
    {
        $officeBuildingHouse->building_block_id = $request->building_block_id;
        $officeBuildingHouse->house_number = $request->house_number;
        $officeBuildingHouse->owner_info = $request->owner_info;
        $officeBuildingHouse->room = $request->room;
        $officeBuildingHouse->hall = $request->hall;
        $officeBuildingHouse->constru_acreage = $request->constru_acreage;
        $officeBuildingHouse->split = $request->split;
        $officeBuildingHouse->min_acreage = $request->min_acreage;
        $officeBuildingHouse->floor = $request->floor;
        $officeBuildingHouse->station_number = $request->station_number;
        $officeBuildingHouse->office_building_type = $request->office_building_type;
        $officeBuildingHouse->register_company = $request->register_company;
        $officeBuildingHouse->open_bill = $request->open_bill;
        $officeBuildingHouse->renovation = $request->renovation;
        $officeBuildingHouse->orientation = $request->orientation;
        $officeBuildingHouse->support_facilities = $request->support_facilities??array();
        $officeBuildingHouse->house_description = $request->house_description;
        $officeBuildingHouse->rent_price = $request->rent_price;
        $officeBuildingHouse->rent_price_unit = $request->rent_price_unit;
        $officeBuildingHouse->payment_type = $request->payment_type;
        $officeBuildingHouse->check_in_time = $request->check_in_time;
        $officeBuildingHouse->shortest_lease = $request->shortest_lease;
        $officeBuildingHouse->rent_free = $request->rent_free;
        $officeBuildingHouse->increasing_situation = $request->increasing_situation;
        $officeBuildingHouse->increasing_situation_remark = $request->increasing_situation_remark;
        $officeBuildingHouse->cost_detail = $request->cost_detail??array();
        $officeBuildingHouse->house_busine_state = $request->house_busine_state;
        $officeBuildingHouse->pay_commission = $request->pay_commission;
        $officeBuildingHouse->pay_commission_unit = $request->pay_commission_unit;
        $officeBuildingHouse->prospecting = $request->prospecting;
        $officeBuildingHouse->source = $request->source;
        $officeBuildingHouse->house_key = $request->house_key;
        $officeBuildingHouse->see_house_time = $request->see_house_time;
        $officeBuildingHouse->see_house_time_remark = $request->see_house_time_remark;
        $officeBuildingHouse->certificate_type = $request->certificate_type;
        $officeBuildingHouse->house_proxy_type = $request->house_proxy_type;
        $officeBuildingHouse->house_type_img = $request->house_type_img;
        $officeBuildingHouse->indoor_img = $request->indoor_img;

        if (!$officeBuildingHouse->save()) {
            return false;
        }

        return true;
    }

    /**
     * 说明: 修改写字楼房源业务状态
     *
     * @param $request
     * @return mixed
     * @author 刘坤涛
     */
    public function updateState($request)
    {
        return $this->model->where('id', $request->id)->update([
            'house_busine_state' => $request->house_busine_state
        ]);
    }
}