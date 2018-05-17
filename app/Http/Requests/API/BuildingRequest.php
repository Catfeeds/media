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
                'building_block' => 'array',
                'describe' => 'max:65535',
            ];
        }
    }
}
