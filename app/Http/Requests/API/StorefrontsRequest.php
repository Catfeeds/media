<?php

namespace App\Http\Requests\API;

use App\Models\Storefront;
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
                    'storefront_name' => [
                        'required',
                        'max:32',
                        Rule::notIn(
                            Storefront::all()->pluck('storefront_name')->toArray()
                        )
                    ],
                    'address' => 'required|max:32',
                    'fixed_tel' => 'required|max:16',
                    'area_manager_id' => [
                        'required',
                        Rule::in(
                            User::where(
                                [
                                    'level' => 2,
                                    'id' => $this->area_manager_id
                                ]
                            )->pluck('id')->toArray()
                        )
                    ]
                ];
            case 'PUT':
                return [

                ];
            case 'PATCH':
                return [
                    'storefront_name' => 'required|max:32',
                    'address' => 'required|max:32',
                    'fixed_tel' => 'required|max:16',
                    'area_manager_id' => [
                        'required',
                        Rule::in(
                            User::where(
                                [
                                    'level' => 2,
                                    'id' => $this->area_manager_id
                                ]
                            )->pluck('id')->toArray()
                        )
                    ]

                ];
            case 'GET':
            case 'DELETE':
            default:
                {
                    return [];
                }
        }
    }
}
