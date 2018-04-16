<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class AgentsRequest extends FormRequest
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
                    'building_blocks_id.required' => '楼座id必须存在',
                    'building_blocks_id.integer' => '楼座id必须为整型数字',
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
                    'building_blocks_id' => [
                        'required',
                        'integer',
                        // TODO 楼座必须存在
                    ],
                    'house_number' => 'required|max:32',
                    'owner_info' => 'required|max:1024',
                    // 房子信息
                    'room' => 'required|numeric|max:9999999999',
                    'hall' => 'required|numeric|max:9999999999',
                    'toilet' => 'required|numeric|max:9999999999',
                    'kitchen' => 'required|numeric|max:9999999999',
                    'balcony' => 'required|numeric|max:9999999999',
                    'constru_acreage' => 'required|max:32',
                    'actual_acreage' => 'max:32',
                    'renovation' => 'nullable|numeric|max:9999',
                    'orientation' => 'max:32',
                    'feature_lable' => 'max:1024',
                    'support_facilities' => 'max:1024',
                    'house_description' => 'max:255',
                    // 租赁信息
                    'rent_price' => 'required|numeric|max:9999999999',
                    'payment_type' => 'required|max:32',
                    'renting_style' => 'max:32',
                    'check_in_time' => 'date',
                    'shortest_lease' => 'max:32',
                    'cost_detail' => 'max:1024',
                    // 业务信息
                    'house_nature' => 'required|max:32',
                    'source' => 'max:32',
                    'actuality' => 'max:32',
                    'payment' => 'nullable|numeric|max:9999999999',
                    'pay_commission' => 'nullable|numeric|max:9999999999',
                    'see_house_time' => 'date',
                    'give_house_time' => 'date',
                    'certificate' => 'max:32',
                    'entrust_number' => 'max:32',
                    'house_key' => 'max:32',
                    'prospecting' => 'required|between:1,2',
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
