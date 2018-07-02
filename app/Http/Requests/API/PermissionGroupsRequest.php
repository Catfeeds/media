<?php

namespace App\Http\Requests\API;

use App\Models\PermissionGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionGroupsRequest extends FormRequest
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
     * 说明: 提示
     *
     * @return array
     * @author 罗振
     */
    public function messages()
    {
        switch ($this->route()->getActionMethod()) {
            case 'store':
                return [
                    'group_name.not_in' => '权限组名称不能重复',
                ];
            default;
                return [

                ];
        }
    }

    /**
     * 说明: 验证规则
     *
     * @return array
     * @author 罗振
     */
    public function rules()
    {
        switch ($this->route()->getActionMethod()) {
            case 'store':
                return [
                    'group_name' => [
                        'required',
                        'max:32',
                        Rule::notIn(
                            PermissionGroup::all()->pluck('group_name')->toArray()
                        )
                    ],
                ];
            default;
                return [

                ];
        }
    }

}
