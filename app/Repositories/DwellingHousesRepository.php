<?php
namespace App\Repositories;

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
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     * @author 罗振
     */
    public function dwellingHousesList()
    {
        return $this->model->all();
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
                'building_blocks_id' => $request->building_blocks_id,
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
                'rent_price_unit' => $request->rent_price_unit,
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

}