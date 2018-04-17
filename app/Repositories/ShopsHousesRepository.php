<?php
namespace App\Repositories;

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
                'building_blocks_id' => $request->building_blocks_id,
                'house_number' => $request->house_number,
                'owner_info' => json_encode($request->owner_info),
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
                'support_facilities' => json_encode($request->support_facilities),
                'fit_management' => json_encode($request->fit_management),
                'house_description' => $request->house_description,
                'rent_price' => $request->rent_price,
                'rent_price_unit' => $request->rent_price_unit,
                'payment_type' => $request->payment_type,
                'check_in_time' => $request->check_in_time,
                'shortest_lease' => $request->shortest_lease,
                'rent_free' => $request->rent_free,
                'increasing_situation' => $request->increasing_situation,
                'transfer_fee' => $request->transfer_fee,
                'cost_detail' => json_encode($request->cost_detail),
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
                'house_type_img' => json_encode($request->house_type_img),
                'indoor_img' => json_encode($request->indoor_img),
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
            \Log::error('商铺房源添加失败：' . $e->getFile() . $e->getLine() . $e->getMessage());
            return false;
        }
    }

}