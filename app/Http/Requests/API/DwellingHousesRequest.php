<?php

namespace App\Http\Requests\API;

use App\Models\BuildingBlock;
use app\Models\DwellingHouse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DwellingHousesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * 说明: 验证错误信息
     *
     * @return array
     * @author 罗振
     */
    public function messages()
    {
        switch ($this->method()) {
            case 'POST':
                return [

                ];
            case 'PUT':
            case 'PATCH':
                {
                    return [

                    ];
                }
            case 'GET':
            case 'DELETE':
            default:
                {
                    return [];
                }
        }
    }

    /**
     * 说明: 字段验证
     *
     * @return array
     * @author 罗振
     */
    public function rules()
    {
        switch ($this->route()->getActionMethod()) {
            case 'updateDwellingBusinessState':
                return [
                    'id' => [
                        'required',
                        'integer',
                        Rule::in(
                            DwellingHouse::all()->pluck('id')->toArray()
                        )
                    ],
                    'house_busine_state' => 'required|integer|between:1,6',
                ];
            case 'store':
                return [
                    // 核心信息
                    'building_block_id' => [
                        'required',
                        'integer',
//                        Rule::in(
//                            BuildingBlock::all()->pluck('id')->toArray()
//                        )
                    ],
                    'house_number' => 'required|max:32',
                    'owner_info' => 'required|array',
                    // 房子信息
                    'room' => 'required|numeric|max:9999999999',
                    'hall' => 'required|numeric|max:9999999999',
                    'toilet' => 'required|numeric|max:9999999999',
                    'kitchen' => 'required|numeric|max:9999999999',
                    'balcony' => 'required|numeric|max:9999999999',
                    'constru_acreage' => 'required|max:32',
                    'floor' => 'nullable|numeric|max:99999999999',

                    'renovation' => 'nullable|integer|between:1,5',
                    'orientation' => 'nullable|integer|between:1,9',
                    'feature_lable' => 'nullable|array',
                    'support_facilities' => 'nullable|array',
                    'house_description' => 'max:255',
                    // 租赁信息
                    'rent_price' => 'required|numeric|max:9999999999',
                    'payment_type' => 'required|integer|between:1,12',
                    'renting_style' => 'required|integer|between:1,2',
                    'check_in_time' => 'nullable|date',
                    'shortest_lease' => 'nullable|integer|between:1,12',
                    'cost_detail' => 'nullable|array',
                    // 业务信息
                    'public_private' => 'required|between:1,3',
                    'house_busine_state' => 'required|integer|between:1,6',
                    'pay_commission' => 'nullable|numeric|max:9999999999',
                    'pay_commission_unit' => 'nullable|integer|between:1,2',
                    'prospecting' => 'nullable|integer|between:1,2',
                    'source' => 'nullable|integer|between:1,7',
                    'house_key' => 'max:32',
                    'see_house_time' => 'nullable|integer|between:1,3',
                    'see_house_time_remark' => 'max:32',
                    'certificate_type' => 'nullable|integer|between:1,7',
                    'house_proxy_type' => 'nullable|integer|between:1,2',
                    'guardian' => 'max:32',
                    // 房源照片
                    'house_type_img' => 'nullable|array',
                    'indoor_img' => 'nullable|array',
                ];
            case 'update':
                return [
                    // 核心信息
                    'building_block_id' => [
                        'integer',
//                        Rule::in(
//                            BuildingBlock::all()->pluck('id')->toArray()
//                        )
                    ],
                    'house_number' => 'max:32',
                    // 房子信息
                    'room' => 'nullable|numeric|max:9999999999',
                    'hall' => 'nullable|numeric|max:9999999999',
                    'toilet' => 'nullable|numeric|max:9999999999',
                    'kitchen' => 'nullable|numeric|max:9999999999',
                    'balcony' => 'nullable|numeric|max:9999999999',
                    'constru_acreage' => 'max:32',
                    'floor' => 'nullable|numeric|max:99999999999',

                    'renovation' => 'nullable|integer|between:1,5',
                    'orientation' => 'nullable|integer|between:1,9',
                    'house_description' => 'max:255',
                    // 租赁信息
                    'rent_price' => 'nullable|numeric|max:9999999999',
                    'payment_type' => 'nullable|integer|between:1,12',
                    'renting_style' => 'nullable|integer|between:1,2',
                    'check_in_time' => 'nullable|date',
                    'shortest_lease' => 'nullable|integer|between:1,12',
                    // 业务信息
                    'public_private' => 'required|integer|between:1,3',
                    'house_busine_state' => 'required|integer|between:1,6',
                    'pay_commission' => 'nullable|numeric|max:9999999999',
                    'pay_commission_unit' => 'nullable|integer|between:1,2',
                    'prospecting' => 'nullable|integer|between:1,2',
                    'source' => 'nullable|integer|between:1,7',
                    'house_key' => 'max:32',
                    'see_house_time' => 'nullable|integer|between:1,3',
                    'see_house_time_remark' => 'max:32',
                    'certificate_type' => 'nullable|integer|between:1,7',
                    'house_proxy_type' => 'nullable|integer|between:1,2',
                    'guardian' => 'max:32',
                    // 房源照片
                    'house_type_img' => 'max:1024',
                    'indoor_img' => 'max:1024',
                ];
            default:
                {
                    return [];
                }
        }
    }
}
