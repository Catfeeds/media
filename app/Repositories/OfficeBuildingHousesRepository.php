<?php
namespace App\Repositories;

use App\Models\OfficeBuildingHouse;
use App\Services\HousesService;

class OfficeBuildingHousesRepository extends BaseRepository
{

    private $model;

    public function __construct(OfficeBuildingHouse $model)
    {
        $this->model = $model;
    }

    public function officeBuildingHousesList()
    {
        return $this->model->all();
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
                'building_blocks_id' => $request->building_blocks_id,
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
                'support_facilities' => $request->support_facilities,
                'house_description' => $request->house_description,
                'rent_price' => $request->rent_price,
                'rent_price_unit' => $request->rent_price_unit,
                'payment_type' => $request->payment_type,
                'check_in_time' => $request->check_in_time,
                'shortest_lease' => $request->shortest_lease,
                'rent_free' => $request->rent_free,
                'increasing_situation' => $request->increasing_situation,
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
}