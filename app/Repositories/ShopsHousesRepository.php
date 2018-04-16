<?php
namespace App\Repositories;

use App\Models\ShopsHouse;

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
     * @return mixed
     * @author 罗振
     */
    public function addShopsHouses($request)
    {
        return $this->model->create([
            'building_blocks_id' => $request->building_blocks_id,
            'house_number' => $request->house_number,
            'owner_info' => json_encode($request->owner_info),
            'floor' => $request->floor,
            'frontage' => $request->frontage,
            'constru_acreage' => $request->constru_acreage,
            'split' => $request->split,
            'min_acreage' => $request->min_acreage,
            'renovation' => $request->renovation,
            'type' => $request->type,
            'orientation' => $request->orientation,
            'wide' => $request->wide,
            'depth' => $request->depth,
            'storey' => $request->storey,
            'supporting' => json_encode($request->supporting),
            'fit_management' => json_encode($request->fit_management),
            'house_description' => $request->house_description,
            'rent_price' => $request->rent_price,
            'payment_type' => $request->payment_type,
            'check_in_time' => strtotime($request->check_in_time),
            'shortest_lease' => $request->shortest_lease,
            'rent_free' => $request->rent_free,
            'increasing_situation' => $request->increasing_situation,
            'transfer_fee' => $request->transfer_fee,
            'cost_detail' => json_encode($request->cost_detail),
            'house_nature' => $request->house_nature,
            'source' => $request->source,
            'actuality' => $request->actuality,
            'payment' => $request->payment,
            'pay_commission' => $request->pay_commission,
            'see_house_time' => strtotime($request->see_house_time),
            'give_house_time' => strtotime($request->give_house_time),
            'certificate' => $request->certificate,
            'entrust_number' => $request->entrust_number,
            'house_key' => $request->house_key,
            'prospecting' => $request->prospecting,
            'guardian' => $request->guardian,
            'house_type_img' => json_encode($request->house_type_img),
            'indoor_img' => json_encode($request->indoor_img),
        ]);
    }
}