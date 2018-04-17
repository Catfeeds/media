<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class BuildingBlockRequest extends FormRequest
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
            case 'addNameUnit':
                return [
                    'building_id' => 'numeric|max:99999999999',
                    'name' => 'required|max:128',
                    'name_unit' => 'required|max:128',
                    'unit' => 'max:128',
                    'unit_unit' => 'max:128'
                ];
            case 'changeNameUnit':
                return [
                    'name' => 'required|max:128',
                    'name_unit' => 'required|max:128',
                    'unit' => 'max:128',
                    'unit_unit' => 'max:128'
                ];
        }
    }
}
