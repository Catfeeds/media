<?php

namespace App\Repositories;

use App\Models\OfficeBuildingHouse;

class OfficeBuildingHousesRepository extends BaseRepository
{

    private $model;

    public function __construct(OfficeBuildingHouse $model)
    {
        $this->model = $model;
    }

    /**
     * 说明: 写字楼房源添加
     *
     * @param $request
     * @return mixed
     * @author 罗振
     */
    public function addOfficeBuildingHouses($request)
    {
        return $this->model->create([
            'building_blocks_id' => $request->building_blocks_id,
            'house_number' => $request->house_number,
            'owner_info' => json_encode($request->owner_info??null),
            'floor' => $request->floor,
            'constru_acreage' => $request->constru_acreage,
            'split' => $request->split,
            'min_acreage' => $request->min_acreage,
            'renovation' => $request->renovation,
            'type' => $request->type,
            'orientation' => $request->orientation,
            'office_furniture' => $request->office_furniture,
            'register_company' => $request->register_company,
            'station_number' => $request->station_number,
            'supporting' => json_encode($request->supporting??null),
            'fit_management' => json_encode($request->fit_management??null),
            'house_description' => $request->house_description,
            'rent_price' => $request->rent_price,
            'payment_type' => $request->payment_type,
            'check_in_time' => strtotime($request->check_in_time??null),
            'shortest_lease' => $request->shortest_lease,
            'rent_free' => $request->rent_free,
            'increasing_situation' => $request->increasing_situation,
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