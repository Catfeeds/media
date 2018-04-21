<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RolesRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => [
                        'required',
                        'string',
                        'min:1',
                        'max:32',
                        'regex:/^[a-z\d\_]*$/i',
                    ],
                    'name_en' => [
                        'required',
                        'string',
                        'min:1',
                        'max:32',
                        'regex:/^[a-z\d\_]*$/i',
                    ],
                    'name_cn' => 'required|min:1|max:32',
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
