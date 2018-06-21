<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class RawCustomsRequest extends FormRequest
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
        switch ($this->route()->getActionMethod()) {
            case 'store':
                return [
                    'name' => 'required|max:32',
                    'tel' =>  'required|max:16',
                    'source' => 'required|between:1,4|integer',
                    'demand' => 'required|between:1,2|integer',
                    'position' => 'nullable',
                    'acreage' => 'nullable|numeric',
                    'price' => 'nullable|numeric',
                    'shopkeeper_id' => 'required|exists:storefronts,user_id',
                    'remark' => 'nullable',
                    'recorder' => 'required'
                ];
            case 'distribution':
                return [
                    'staff_id' => 'required|exists:users,id'
                ];
            case 'PUT':
                return [

                ];
            case 'PATCH':
            case 'GET':
            case 'DELETE':
            default:
                {
                    return [];
                }
        }
    }
}
