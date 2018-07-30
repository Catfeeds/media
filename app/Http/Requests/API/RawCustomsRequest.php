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
                    'source' => 'required|integer',
                    'demand' => 'nullable|integer',
                    'position' => 'nullable',
                    'acreage' => 'nullable',
                    'price' => 'nullable',
                    'shopkeeper_id' => 'nullable|exists:users,id',
                    'remark' => 'nullable',
                    'recorder' => 'required'
                ];
            case 'distribution':
                return [
                    'staff_id' => 'required|exists:users,id'
                ];
            case 'feedback':
                return [
                    'feedback' => 'required',
                    'valid' => 'required'
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
