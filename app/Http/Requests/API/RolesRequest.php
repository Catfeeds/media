<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

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
     * 说明: 验证错误信息
     *
     * @return array
     * @author 罗振
     */
    public function messages()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'permissions.*.in' => '权限必须存在'
                ];
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'permissions.*.in' => '权限必须存在'
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
                    'name_en' => [
                        'required',
                        'string',
                        'min:1',
                        'max:32',
                        'regex:/^[a-z\d\_]*$/i',
                    ],
                    'name_cn' => 'required|min:1|max:32',
                    'permissions' => 'required|array',
                    'permissions.*' => [
                        'required',
                        Rule::in(
                            Permission::where('guard_name', 'web')->pluck('name')->toArray()
                        )
                    ]
                ];
            case 'PUT':
                return [

                ];
            case 'PATCH':
                return[
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
                    'permissions' => 'required|array',
                    'permissions.*' => [
                        'required',
                        Rule::in(
                            Permission::where('guard_name', 'web')->pluck('name')->toArray()
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
