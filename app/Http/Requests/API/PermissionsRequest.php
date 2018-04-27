<?php

namespace App\Http\Requests\API;

use App\Models\Permission;
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
     * 说明: 验证错误信息
     *
     * @return array
     * @author 罗振
     */
    public function messages()
    {
        switch ($this->route()->getActionMethod()) {
            case 'POST':
                return [
                    'group_id.in' => '权限组必须存在'
                ];
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'group_id.in' => '权限组必须存在'
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
                    'label' => 'required|string|min:1|max:32',
                    'group_id' => [
                        'required',
                        Rule::in(
                            PermissionGroup::where('guard_name', 'admin')->pluck('id')->toArray()
                        )
                    ],
                ];
            case 'PUT':
                return [

                ];
            case 'PATCH':
                return [
                    'name' => [
                        'required',
                        'string',
                        'min:1',
                        'max:32',
                        'regex:/^[a-z\d\_]*$/i',
                    ],
                    'label' => 'required|string|min:1|max:32',
                    'group_id' => [
                        'required',
                        Rule::in(
                            PermissionGroup::where('guard_name', 'admin')->pluck('id')->toArray()
                        )
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
