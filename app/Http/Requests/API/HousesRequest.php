<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class HousesRequest extends FormRequest
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
     * 说明: 字段验证
     *
     * @return array
     * @author 刘坤涛
     */
    public function rules()
    {
        switch ($this->route()->getActionMethod()) {
            case 'getOwnerInfo':
                return [
                    'house_model' => 'required|integer|between:1,3',
                    'house_id' => 'required|integer',
                ];
            default:
                {
                    return [];
                }
        }
    }
}
