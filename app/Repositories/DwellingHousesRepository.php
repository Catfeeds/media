<?php

namespace App\Repositories;

use App\Models\DwellingHouse;

class DwellingHousesRepository extends BaseRepository
{

    private $model;

    public function __construct(DwellingHouse $model)
    {
        $this->model = $model;
    }

    /**
     * 说明: 住宅房源添加
     *
     * @param $request
     * @return mixed
     * @author 罗振
     */
    public function addDwellingHouses($request)
    {
        return $this->model->create([
            'building_blocks_id' => $request->building_blocks_id,
            'house_number' => $request->house_number,
            'owner_info' => json_encode($request->owner_info??null),
            'room' => $request->room,
            'hall' => $request->hall,
            'toilet' => $request->toilet,
            'kitchen' => $request->kitchen,
            'balcony' => $request->balcony,
            'floor' => $request->floor,
            'constru_acreage' => $request->constru_acreage,
            'actual_acreage' => $request->actual_acreage,
            'renovation' => $request->renovation,
            'orientation' => $request->orientation,
            'feature_lable' => json_encode($request->feature_lable??null),
            'support_facilities' => json_encode($request->support_facilities??null),
            'house_description' => $request->house_description,
            'rent_price' => $request->rent_price,
            'payment_type' => $request->payment_type,
            'check_in_time' => strtotime($request->check_in_time??null),
            'shortest_lease' => $request->shortest_lease,
            'cost_detail' => json_encode($request->cost_detail??null),
            'house_nature' => $request->house_nature,
            'source' => $request->source,
            'actuality' => $request->actuality,
            'payment' => $request->payment,
            'pay_commission' => $request->pay_commission,
            'see_house_time' => strtotime($request->see_house_time??null),
            'give_house_time' => strtotime($request->give_house_time??null),
            'certificate' => $request->certificate,
            'entrust_number' => $request->entrust_number,
            'house_key' => $request->house_key,
            'prospecting' => $request->prospecting,
            'guardian' => $request->guardian,
            'house_type_img' => json_encode($request->house_type_img??null),
            'indoor_img' => json_encode($request->indoor_img??null),
        ]);
    }

}