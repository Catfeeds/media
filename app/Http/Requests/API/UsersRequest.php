<?php

namespace App\Http\Requests\API;

use App\Models\Storefront;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UsersRequest extends FormRequest
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
     * 说明: 验证错误信息
     *
     * @return array
     * @author 罗振
     */
    public function messages()
    {
        switch ($this->route()->getActionMethod()) {
            case 'store':
                return [
                    'ascription_store.in' => '门店必须存在',
                    'tel.not_in' => '手机号不能重复'
                ];
            case 'update':
                {
                    return [
                        'ascription_store.in' => '门店必须存在',
                    ];
                }
            default:
                {
                    return [];
                }
        }
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
            case 'store':
                return [
                    'real_name' => [
                        'required',
                        'max:32',
                    ],
                    'ascription_store' => [
                        'nullable',
                        'integer',
                        Rule::in(
                            Storefront::all()->pluck('id')->toArray()
                        )
                    ],
                    'level' => 'required|integer|between:1,4',
                    'tel' => [
                        'required',
                        'max:16',
                        Rule::notIn(
                            User::all()->pluck('tel')->toArray()
                        )
                    ],
                    'remark' => 'nullable|max:255',
                ];
            case 'update':
                return [
                    'real_name' => [
                        'required',
                        'max:32',
                    ],
                    'ascription_store' => [
                        'nullable',
                        'integer',
                        Rule::in(
                            Storefront::all()->pluck('id')->toArray()
                        )
                    ],
                    'level' => 'required|integer|between:1,4',
                    'remark' => 'nullable|max:255',
                ];
            case 'updatePassword':
                return [
                    'password' => 'required|min:6|max:18',
                ];
            case 'updateTel':
                return [
                    'tel' => [
                        'required',
                        'max:16',
                    ],
                ];
            default:
                {
                    return [];
                }
        }
    }
}
