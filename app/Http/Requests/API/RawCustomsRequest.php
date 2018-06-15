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
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|max:32',
                    'tel' =>  'required|max:16',
                    'source' => 'required|between:1,4|integer',
                    'demand' => 'required|between:1,3|integer',
                    'position' => 'nullable',
                    'acreage' => 'nullable|numeric',
                    'price' => 'nullable|numeric',
                    'shopkeeper_id' => 'required|exists:storefronts,user_id',
                    'remark' => 'nullable',
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
