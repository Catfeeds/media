<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class CustomRequest extends FormRequest
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
            case 'store':
            case 'update':
                return [
                    'status' => 'required|numeric|max:100',
                    'class' => 'required|numeric|max:100',
                    'source' => 'required|numeric|max:100',
                    'name' => 'required|max:32',
                    'tel' => 'required|max:32',
                    'price_low' => 'nullable|numeric|max:99999999999',
                    'price_high' => 'nullable|numeric|max:99999999999',
                    'payment_type' => 'nullable|numeric|max:100',
                    'pay_commission' => 'nullable|numeric|max:9999999999',
                    'pay_commission_unit' => 'nullable|numeric|max:100',
                    'need_type' => 'nullable|numeric|max:100',
                    'renting_style' => 'nullable|numeric|max:100',
                    'office_building_type' => 'nullable|numeric|max:100',
                    'shops_type' => 'nullable|numeric|max:100',
                    'acre_low' => 'nullable|numeric|max:99999999999',
                    'acre_high' => 'nullable|numeric|max:99999999999',
                    'room' => 'nullable|numeric|max:99999999999',
                    'hall' => 'nullable|numeric|max:99999999999',
                    'toilet' => 'nullable|numeric|max:99999999999',
                    'kitchen' => 'nullable|numeric|max:99999999999',
                    'balcony' => 'nullable|numeric|max:99999999999',
                    'floor_low' => 'nullable|numeric|max:99999999999',
                    'floor_high' => 'nullable|numeric|max:99999999999',
                    'renovation' => 'nullable|numeric|max:100',
                    'orientation' => 'nullable|numeric|max:100',
                    'subway' => 'max:128',
                    'walk_to_subway' => 'nullable|numeric|max:99999999999',
                    'bus' => 'max:128',
                    'walk_to_bus' => 'nullable|numeric|max:99999999999',
                    'like' => 'max:256',
                    'not_like' => 'max:256',
                    'area_id' => 'nullable|numeric|max:99999999999',

                    'buildings' => 'nullable|array',
                    'areas' => 'nullable|array',
                    'other' => 'nullable|array'
                ];
            case 'updateStatus':
                return [
                    'status' => 'required|numeric|max:100'
                ];
        }
    }
}
