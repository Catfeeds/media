<?php

namespace App\Http\Requests\API;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorefrontsRequest extends FormRequest
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
                    'storefront_name' => 'required|max:32',
                    'address' => 'required|max:32',
                    'user_id' => [
                        'required',
                        'integer',
//                        Rule::in(
//                            User::all()->pluck('id')->toArray()
//                        )
                    ],
                    'fixed_tel' => 'required|max:16',
                ];
            case 'PUT':
                return [

                ];
            case 'PATCH':
                {
                    return [
                        'storefront_name' => 'required|max:32',
                        'address' => 'required|max:32',
                        'user_id' => [
                            'required',
                            'integer',
//                        Rule::in(
//                            User::all()->pluck('id')->toArray()
//                        )
                        ],
                        'fixed_tel' => 'required|max:16',
                    ];
                }
            case 'GET':
            case 'DELETE':
            default:
                {
                    return [];
                }
        }
    }
}
