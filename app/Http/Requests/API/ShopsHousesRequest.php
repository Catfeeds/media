<?php

namespace App\Http\Requests\API;

use App\Models\BuildingBlock;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShopsHousesRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                return [
                    // 核心信息
                    'building_blocks_id' => [
                        'required',
                        'integer',
//                        Rule::in(
//                            BuildingBlock::all()->pluck('id')->toArray()
//                        )
                    ],
                    'house_number' => 'required|max:32',
                    'owner_info' => 'required',
                    // 房子信息
                    'constru_acreage' => 'required|max:32',
                    'split' => 'nullable|between:1,2',
                    'min_acreage' => 'nullable|numeric|max:99999999999',
                    'floor' => 'nullable|numeric|max:99999999999',
                    'frontage' => 'nullable|between:1,2',
                    'renovation' => 'nullable|between:1,2,3,4,5',
                    'shops_type' => 'nullable|between:1,2,3,4,5,6,7',
                    'orientation' => 'nullable|between:1,2,3,4,5,6,7,8,9',
                    'wide' => 'max:32',
                    'depth' => 'max:32',
                    'storey' => 'max:32',
                    'house_description' => 'max:255',
                    // 租赁信息
                    'rent_price' => 'required|numeric|max:9999999999',
                    'rent_price_unit' => 'nullable|between:1,2',
                    'payment_type' => 'required|between:1,2,3,4,5,6,7,8,9,10,11,12',
                    'check_in_time' => 'date',
                    'shortest_lease' => 'nullable|between:1,2,3,4,5',
                    'rent_free' => 'nullable|between:1,2,3,4,5,6,7,8,9,10,11',
                    'increasing_situation' => 'max:32',
                    'transfer_fee' => 'nullable|numeric|max:99999999999',
                    // 业务信息
                    'public_private' => 'required|between:1,2',
                    'house_busine_state' => 'required|between:1,2,3,4,5,6',
                    'pay_commission' => 'nullable|numeric|max:9999999999',
                    'pay_commission_unit' => 'nullable|between:1,2',
                    'prospecting' => 'nullable|between:1,2',
                    'source' => 'nullable|between:1,2,3,4,5,6,7',
                    'house_key' => 'max:32',
                    'see_house_time' => 'nullable|between:1,2,3',
                    'see_house_time_remark' => 'max:32',
                    'certificate_type' => 'nullable|between:1,2,3,4,5,6,7',
                    'house_proxy_type' => 'nullable|between:1,2',
                    'guardian' => 'max:32',
                    // 房源照片
                    'house_type_img' => 'max:1024',
                    'indoor_img' => 'max:1024',
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
}
