<?php

namespace App\Http\Requests\API;

use App\Models\BuildingBlock;
use App\Models\ShopsHouse;
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
        switch ($this->route()->getActionMethod()) {
            case 'updateShopsBusinessState':
                return [
                    'id.in' => '商铺房源必须存在'
                ];
            case 'store':
                return [
                    'building_block_id.in' => '楼座必须存在'
                ];
            case 'update':
                {
                    return [
                        'building_block_id.in' => '楼座必须存在'
                    ];
                }
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
            case 'updateShopsBusinessState':
                return [
                    'id' => [
                        'required',
                        'integer',
                        Rule::in(
                            ShopsHouse::all()->pluck('id')->toArray()
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
                        Rule::in(
                            BuildingBlock::all()->pluck('id')->toArray()
                        )
                    ],
                    'house_number' => 'required|max:32',
                    'owner_info' => 'required',
                    // 房子信息
                    'constru_acreage' => 'required|max:32',
                    'split' => 'nullable|integer|between:1,2',
                    'min_acreage' => 'nullable|numeric|max:99999999999',
                    'floor' => 'nullable|numeric|max:99999999999',
                    'frontage' => 'nullable|integer|between:1,2',
                    'renovation' => 'nullable|integer|between:1,5',
                    'shops_type' => 'nullable|integer|between:1,7',
                    'orientation' => 'nullable|integer|between:1,9',
                    'wide' => 'max:32',
                    'depth' => 'max:32',
                    'storey' => 'max:32',
                    'house_description' => 'max:255',
                    // 租赁信息
                    'rent_price' => 'required|numeric|max:9999999999',
                    'rent_price_unit' => 'nullable|integer|between:1,2',
                    'payment_type' => 'required|integer|between:1,12',
                    'check_in_time' => 'nullable|date',
                    'shortest_lease' => 'nullable|integer|between:1,5',
                    'rent_free' => 'nullable|integer|between:1,11',
                    'increasing_situation' => 'max:32',
                    'increasing_situation_remark' => 'nullable|max:256',
                    'transfer_fee' => 'nullable|numeric|max:99999999999',
                    'transfer_fee_remark' => 'nullable|max:256',
                    // 业务信息
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
                    // 房源照片
                    'house_type_img' => 'max:1024',
                    'indoor_img' => 'max:1024',
                ];
            case 'update':
                return [
                    // 核心信息
                    'building_block_id' => [
                        'integer',
                        Rule::in(
                            BuildingBlock::all()->pluck('id')->toArray()
                        )
                    ],
                    'house_number' => 'max:32',
                    // 房子信息
                    'constru_acreage' => 'max:32',
                    'split' => 'nullable|integer|between:1,2',
                    'min_acreage' => 'nullable|numeric|max:99999999999',
                    'floor' => 'nullable|numeric|max:99999999999',
                    'frontage' => 'nullable|integer|between:1,2',
                    'renovation' => 'nullable|integer|between:1,5',
                    'shops_type' => 'nullable|integer|between:1,7',
                    'orientation' => 'nullable|integer|between:1,9',
                    'wide' => 'max:32',
                    'depth' => 'max:32',
                    'storey' => 'max:32',
                    'house_description' => 'max:255',
                    // 租赁信息
                    'rent_price' => 'nullable|numeric|max:9999999999',
                    'rent_price_unit' => 'nullable|integer|between:1,2',
                    'payment_type' => 'nullable|integer|between:1,12',
                    'check_in_time' => 'nullable|date',
                    'shortest_lease' => 'nullable|integer|between:1,5',
                    'rent_free' => 'nullable|integer|between:1,11',
                    'increasing_situation' => 'max:32',
                    'increasing_situation_remark' => 'nullable|max:256',
                    'transfer_fee' => 'nullable|numeric|max:99999999999',
                    'transfer_fee_remark' => 'nullable|max:256',
                    // 业务信息
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
