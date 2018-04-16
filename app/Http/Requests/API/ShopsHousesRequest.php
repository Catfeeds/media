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
                    // 核心信息
                    'building_blocks_id.required' => '楼座id必须存在',
                    'building_blocks_id.integer' => '楼座id必须为整型数字',
                    'building_blocks_id.in' => '楼座必须存在',
                    'house_number.required' => '房号必须存在',
                    'house_number.max' => '房号最大长度为32位',
                    'owner_info.required' => '业主信息必须存在',
                    // 房子信息
                    'floor.numeric' => '楼层必须为数字',
                    'floor.max' => '楼层最大长度为11位',
                    'frontage.between' => '是否临街异常',
                    'constru_acreage.required' => '建筑面积不能为空',
                    'constru_acreage.max' => '建筑面积最大长度为32位',
                    'split.between' => '可拆分异常',
                    'min_acreage.numeric' => '最小面积必须是数字',
                    'min_acreage.max' => '最小面积最大长度为32位',
                    'renovation.numeric' => '装修必须为数字',
                    'renovation.max' => '装修最大长度为4位',
                    'type.numeric' => '类型必须为数字',
                    'type.max' => '类型最大长度为4位',
                    'orientation.max' => '朝向最大长度位32位',
                    'wide.max' => '面宽最大长度位32位',
                    'depth.max' => '进深最大长度位32位',
                    'storey.max' => '层高最大长度位32位',
                    'house_description.max' => '房源描述最大长度为255位',
                    // 租赁信息
                    'rent_price.required' => '租金不能为空',
                    'rent_price.numeric' => '租金必须为数字',
                    'rent_price.max' => '租金最大长度为10位',
                    'payment_type.required' => '支付方式不能为空',
                    'payment_type.max' => '支付方式最大长度为32位',
                    'shortest_lease.max' => '最短租期最大长度为32位',
                    'rent_free.max' => '免租期最大长度为32位',
                    'increasing_situation.max' => '递增情况最大长度为32位',
                    'transfer_fee.numeric' => '转让费必须为数字',
                    'transfer_fee.max' => '转让费最大长度为10位',
                    // 业务信息
                    'house_nature.required' => '房源性质不能为空',
                    'house_nature.max' => '房源性质最大长度为32位',
                    'source.max' => '来源最大长度为32位',
                    'actuality.max' => '现状最大长度为32位',
                    'payment.numeric' => '付款必须为数字',
                    'payment.max' => '付款最大长度为10位',
                    'pay_commission.numeric' => '付佣必须为数字',
                    'pay_commission.max' => '付佣款最大长度为10位',
                    'certificate.max' => '证件最大长度为32位',
                    'entrust_number.max' => '委托编号最大长度为32位',
                    'house_key.max' => '钥匙最大长度为32位',
                    'prospecting.between' => '是否实勘异常',
                    'guardian.max' => '维护人最大长度为32位',
                    // 房源照片
                    'house_type_img.max' => '户型图最大长度为1024位',
                    'indoor_img.max' => '室内图最大长度为1024位',
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
                        Rule::in(
                            BuildingBlock::all()->pluck('id')->toArray()
                        )
                    ],
                    'house_number' => 'required|max:32',
                    'owner_info' => 'required',
                    // 房子信息
                    'floor' => 'nullable|numeric|max:99999999999',
                    'frontage' => 'nullable|between:1,2',
                    'constru_acreage' => 'required|max:32',
                    'split' => 'nullable|between:1,2',
                    'min_acreage' => 'nullable|numeric|max:99999999999',
                    'renovation' => 'nullable|numeric|max:9999',
                    'type' => 'nullable|numeric|max:9999',
                    'orientation' => 'max:32',
                    'wide' => 'max:32',
                    'depth' => 'max:32',
                    'storey' => 'max:32',
                    'house_description' => 'max:255',
                    // 租赁信息
                    'rent_price' => 'required|numeric|max:9999999999',
                    'payment_type' => 'required|max:32',
                    'check_in_time' => 'date',
                    'shortest_lease' => 'max:32',
                    'rent_free' => 'max:32',
                    'increasing_situation' => 'max:32',
                    'transfer_fee' => 'nullable|numeric|max:99999999999',
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
