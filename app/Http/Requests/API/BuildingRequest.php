<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class BuildingRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = $this->route()->getActionMethod();

        switch ($method) {
            case 'update':
            case 'store':
            return [
                'name' => 'required|max:128',
                'gps' => 'required',
                'type' => 'required|numeric|max:100',

                'area_id' => 'required:numeric',
                'block_id' => 'nullable|numeric',
                'address' => 'required|max:128',

                'developer' => 'nullable|max:128',
                'years' => 'nullable|numeric|max:10000',
                'acreage' => 'nullable|numeric|max:99999999999',
                'building_block_num' => 'nullable|numeric|max:10000',
                'parking_num' => 'nullable|numeric|max:10000',
                'parking_fee' => 'nullable|numeric|max:10000',
                'greening_rate' => 'nullable|numeric|max:100',

                'company' => 'array',
                'album' => 'array',
                'building_block' => 'array'
            ];
        }
    }

//    public function messages()
//    {
//        return [
//            'name.required' => '楼盘名称必填',
//            'name.max' => '楼盘名称最多128个字符',
//            'gps.required' => 'gps定位必填',
//
//            'street_id.required' => '街道id必填',
//            'street_id.numeric' => '街道id为数字类型',
//
//            'block_id.numeric' => '街道id为数字类型',
//            'address.required' => '商圈id必填',
//            'address.max' => '地址最大128个字符',
//
//            'developer.max' => 'max:128',
//
//            'years.numeric' => 'numeric|max:10000',
//            'years.max' => 'numeric|max:10000',
//
//            'acreage.numeric' => 'numeric|max:99999999999',
//            'acreage.max' => 'numeric|max:99999999999',
//
//            'building_block.numeric' => 'numeric|max:10000',
//            'building_block.max' => 'numeric|max:10000',
//
//            'parking_num.numeric' => 'numeric|max:10000',
//            'parking_num.max' => 'numeric|max:10000',
//
//            'parking_fee.numeric' => 'numeric|max:10000',
//            'parking_fee.max' => 'numeric|max:10000',
//
//            'greening_rate.numeric' => 'numeric|max:100',
//            'greening_rate.max' => 'numeric|max:100',
//
//            'company.array' => 'array',
//            'album.array' => 'array'
//        ];
//    }
}
