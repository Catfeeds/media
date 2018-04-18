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
                    'status' => 'numeric|max:100',
                    'class' => 'numeric|max:100',
                    'source' => 'numeric|max:100',
                    'belong' => 'numeric|max:100',
                    'name' => 'max:32',
                    'tel' => 'max:32',
                    'price_low' => 'numeric|max:99999999999',
                    'price_high' => 'numeric|max:99999999999',
                    'payment_type' => 'numeric|max:100',
                    'pay_commission' => 'numeric|max:9999999999',
                    'pay_commission_unit' => 'numeric|max:100',
                    'need_type' => 'numeric|max:100',
                    'renting_style' => 'nullable|numeric|max:100',
                    'office_building_type' => 'nullable|numeric|max:100',
                    'shops_type' => 'nullable|numeric|max:100',
                    'acre_low' => 'numeric|max:99999999999',
                    'acre_high' => 'numeric|max:99999999999',
                    'room' => 'numeric|max:99999999999',
                    'hall' => 'numeric|max:99999999999',
                    'toilet' => 'numeric|max:99999999999',
                    'kitchen' => 'numeric|max:99999999999',
                    'balcony' => 'numeric|max:99999999999',
                    'floor_low' => 'numeric|max:99999999999',
                    'floor_high' => 'numeric|max:99999999999',
                    'renovation' => 'numeric|max:100',
                    'orientation' => 'numeric|max:100',
                    'subway' => 'numeric|max:100',
                    'walk_to_subway' => 'numeric|max:99999999999',
                    'bus' => 'max:128',
                    'walk_to_bus' => 'numeric|max:99999999999',
                    'like' => 'max:256',
                    'not_like' => 'max:256',
                    'area_id' => 'numeric|max:99999999999',

                    'buildings' => 'array',
                    'areas' => 'array',
                    'other' => 'array'
                ];
        }
    }
}
