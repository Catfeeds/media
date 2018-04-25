<?php
namespace App\Repositories;

use App\Models\Area;
use App\Models\Building;
use App\Models\ShopsHouse;
use App\Services\HousesService;

class ShopsHousesRepository extends BaseRepository
{

    private $model;

    public function __construct(ShopsHouse $model)
    {
        $this->model = $model;
    }

    /**
     * 说明: 商铺房源列表
     *
     * @param $per_page
     * @param $condition
     * @return mixed
     * @author 罗振
     */
    public function shopsHousesList(
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
     * 说明: 商铺房源添加
     *
     * @param $request
     * @param HousesService $housesService
     * @return bool
     * @author 罗振
     */
    public function addShopsHouses($request, HousesService $housesService)
    {
        \DB::beginTransaction();
        try {
            $house = $this->model->create([
                'building_block_id' => $request->building_block_id,
                'house_number' => $request->house_number,
                'owner_info' => $request->owner_info,
                'constru_acreage' => $request->constru_acreage,
                'split' => $request->split,
                'min_acreage' => $request->min_acreage,
                'floor' => $request->floor,
                'frontage' => $request->frontage,
                'shops_type' => $request->shops_type,
                'renovation' => $request->renovation,
                'orientation' => $request->orientation,
                'wide' => $request->wide,
                'depth' => $request->depth,
                'storey' => $request->storey,
                'support_facilities' => $request->support_facilities,
                'fit_management' => $request->fit_management,
                'house_description' => $request->house_description,
                'rent_price' => $request->rent_price,
                'rent_price_unit' => $request->rent_price_unit,
                'payment_type' => $request->payment_type,
                'check_in_time' => $request->check_in_time,
                'shortest_lease' => $request->shortest_lease,
                'rent_free' => $request->rent_free,
                'increasing_situation' => $request->increasing_situation,
                'transfer_fee' => $request->transfer_fee,
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
                throw new \Exception('商铺房源添加失败');
            }

            $house->house_identifier = $housesService->setHouseIdentifier('S', $house->id);
            if (empty($house->save())) {
                throw new \Exception('商铺房源编号添加失败');
            }

            \DB::commit();
            return $house;
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('商铺房源添加失败：' . $e->getFile() . $e->getLine() . $e->getMessage());
            return false;
        }
    }

    /**
     * 说明: 商铺房源修改
     *
     * @param $shopsHouse
     * @param $request
     * @return bool
     * @author 罗振
     */
    public function updateShopsHouses($shopsHouse, $request)
    {
        $shopsHouse->building_block_id = $request->building_block_id;
        $shopsHouse->house_number = $request->house_number;
        $shopsHouse->owner_info = $request->owner_info;
        $shopsHouse->constru_acreage = $request->constru_acreage;
        $shopsHouse->split = $request->split;
        $shopsHouse->min_acreage = $request->min_acreage;
        $shopsHouse->floor = $request->floor;
        $shopsHouse->frontage = $request->frontage;
        $shopsHouse->shops_type = $request->shops_type;
        $shopsHouse->renovation = $request->renovation;
        $shopsHouse->orientation = $request->orientation;
        $shopsHouse->wide = $request->wide;
        $shopsHouse->depth = $request->depth;
        $shopsHouse->storey = $request->storey;
        $shopsHouse->support_facilities = $request->support_facilities;
        $shopsHouse->fit_management = $request->fit_management;
        $shopsHouse->house_description = $request->house_description;
        $shopsHouse->rent_price = $request->rent_price;
        $shopsHouse->rent_price_unit = $request->rent_price_unit;
        $shopsHouse->payment_type = $request->payment_type;
        $shopsHouse->check_in_time = $request->check_in_time;
        $shopsHouse->shortest_lease = $request->shortest_lease;
        $shopsHouse->rent_free = $request->rent_free;
        $shopsHouse->increasing_situation = $request->increasing_situation;
        $shopsHouse->transfer_fee = $request->transfer_fee;
        $shopsHouse->cost_detail = $request->cost_detail;
        $shopsHouse->public_private = $request->public_private;
        $shopsHouse->house_busine_state = $request->house_busine_state;
        $shopsHouse->pay_commission = $request->pay_commission;
        $shopsHouse->pay_commission_unit = $request->pay_commission_unit;
        $shopsHouse->prospecting = $request->prospecting;
        $shopsHouse->source = $request->source;
        $shopsHouse->house_key = $request->house_key;
        $shopsHouse->see_house_time = $request->see_house_time;
        $shopsHouse->see_house_time_remark = $request->see_house_time_remark;
        $shopsHouse->certificate_type = $request->certificate_type;
        $shopsHouse->house_proxy_type = $request->house_proxy_type;
        $shopsHouse->guardian = $request->guardian;
        $shopsHouse->house_type_img = $request->house_type_img;
        $shopsHouse->indoor_img = $request->indoor_img;

        if (!$shopsHouse->save()) {
            return false;
        }

        return true;
    }

    /**
     * 说明: 修改商铺房源业务状态
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