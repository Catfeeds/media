<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\PermissionGroup;


class PermissionsRequest extends FormRequest
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
                    'name' => 'required|max:255',
                    'label' => 'required|max:128',
                    'group_id' => [
                        'required',
                        'integer',
//                            Rule::in(
//                                PermissionGroup::all()->pluck('id')->toArray()
//                            )
                    ],
                ];
            case 'PUT':
                return [

                ];
            case 'PATCH':
                return [
                    'name' => 'required|max:255',
                    'label' => 'required|max:128',
                    'group_id' => [
                        'required',
                        'integer',
//                            Rule::in(
//                                PermissionGroup::all()->pluck('id')->toArray()
//                            )
                    ],
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
