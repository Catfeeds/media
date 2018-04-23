<?php
namespace App\Repositories;

use App\Models\Area;
use App\Models\Building;
use App\Models\DwellingHouse;
use App\Services\HousesService;

class DwellingHousesRepository extends BaseRepository
{
    private $model;

    public function __construct(DwellingHouse $model)
    {
        $this->model = $model;
    }

    /**
     * 说明: 住宅房源列表
     *
     * @param $per_page
     * @param $condition
     * @return mixed
     * @author 罗振
     */
    public function dwellingHousesList(
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
            $result = $result->where('constru_acreage', ">", (int)$condition->min_acreage);
        }
        // 最大面积
        if (!empty($condition->max_acreage)) {
            $result = $result->where('constru_acreage', "<", (int)$condition->max_acreage);
        }

        // 排序
        if (!empty($condition->order)) {
            $result = $result->orderBy('updated_at', $condition->order);
        }

        return $result->paginate($per_page??10);
    }

    /**
     * 说明: 住宅房源添加
     *
     * @param $request
     * @param HousesService $housesService
     * @return bool
     * @author 罗振
     */
    public function addDwellingHouses($request, HousesService $housesService)
    {
        \DB::beginTransaction();
        try {
            $house = $this->model->create([
                'building_block_id' => $request->building_block_id,
                'house_number' => $request->house_number,
                'owner_info' => $request->owner_info,
                'room' => $request->room,
                'hall' => $request->hall,
                'toilet' => $request->toilet,
                'kitchen' => $request->kitchen,
                'balcony' => $request->balcony,
                'constru_acreage' => $request->constru_acreage,
                'floor' => $request->floor,
                'renovation' => $request->renovation,
                'orientation' => $request->orientation,
                'feature_lable' => $request->feature_lable,
                'support_facilities' => $request->support_facilities,
                'house_description' => $request->house_description,
                'rent_price' => $request->rent_price,
                'payment_type' => $request->payment_type,
                'renting_style' => $request->renting_style,
                'check_in_time' => $request->check_in_time,
                'shortest_lease' => $request->shortest_lease,
                'cost_detail' => $request->cost_detail,
                'public_private' => $request->public_private,
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
                'guardian' => $request->guardian,
                'house_type_img' => $request->house_type_img,
                'indoor_img' => $request->indoor_img,
            ]);
            if (empty($house)) {
                throw new \Exception('住宅房源添加失败');
            }

            $house->house_identifier = $housesService->setHouseIdentifier('Z', $house->id);
            if (empty($house->save())) {
                throw new \Exception('住宅房源编号添加失败');
            }

            \DB::commit();
            return $house;
        } catch (\Exception $e) {
            \Log::error('住宅房源添加失败：' . $e->getFile() . $e->getLine() . $e->getMessage());
            return false;
        }
    }

    /**
     * 说明: 住宅房源修改操作
     *
     * @param $dwellingHouse
     * @param $request
     * @return bool
     * @author 罗振
     */
    public function updateDwellingHouses($dwellingHouse, $request)
    {
        $dwellingHouse->building_block_id = $request->building_block_id;
        $dwellingHouse->house_number = $request->house_number;
        $dwellingHouse->owner_info = $request->owner_info;
        $dwellingHouse->room = $request->room;
        $dwellingHouse->hall = $request->hall;
        $dwellingHouse->toilet = $request->toilet;
        $dwellingHouse->kitchen = $request->kitchen;
        $dwellingHouse->balcony = $request->balcony;
        $dwellingHouse->constru_acreage = $request->constru_acreage;
        $dwellingHouse->floor = $request->floor;
        $dwellingHouse->renovation = $request->renovation;
        $dwellingHouse->orientation = $request->orientation;
        $dwellingHouse->feature_lable = $request->feature_lable;
        $dwellingHouse->support_facilities = $request->support_facilities;
        $dwellingHouse->house_description = $request->house_description;
        $dwellingHouse->rent_price = $request->rent_price;
        $dwellingHouse->payment_type = $request->payment_type;
        $dwellingHouse->renting_style = $request->renting_style;
        $dwellingHouse->check_in_time = $request->check_in_time;
        $dwellingHouse->shortest_lease = $request->shortest_lease;
        $dwellingHouse->cost_detail = $request->cost_detail;
        $dwellingHouse->public_private = $request->public_private;
        $dwellingHouse->house_busine_state = $request->house_busine_state;
        $dwellingHouse->pay_commission = $request->pay_commission;
        $dwellingHouse->pay_commission_unit = $request->pay_commission_unit;
        $dwellingHouse->prospecting = $request->prospecting;
        $dwellingHouse->source = $request->source;
        $dwellingHouse->house_key = $request->house_key;
        $dwellingHouse->see_house_time = $request->see_house_time;
        $dwellingHouse->see_house_time_remark = $request->see_house_time_remark;
        $dwellingHouse->certificate_type = $request->certificate_type;
        $dwellingHouse->house_proxy_type = $request->house_proxy_type;
        $dwellingHouse->guardian = $request->guardian;
        $dwellingHouse->house_type_img = $request->house_type_img;
        $dwellingHouse->indoor_img = $request->indoor_img;

        if (!$dwellingHouse->save()) {
            return false;
        }

        return true;
    }

    /**
     * 说明: 修改住宅房源业务状态
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