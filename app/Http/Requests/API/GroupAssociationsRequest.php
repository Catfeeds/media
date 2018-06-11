<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class GroupAssociationsRequest extends FormRequest
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
                    'name.unique' => '组名不能重复',
                    'group_leader_id.exists' => '该成员必须存在'
                ];
            case 'update':
                return [
                    'name.unique' => '组名不能重复',
                    'group_leader_id.exists' => '该成员必须存在'
                ];
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
     * @author 罗振
     */
    public function rules()
    {
        switch ($this->route()->getActionMethod()) {
            case 'store':
                return [
                    'name' => 'required|unique:group_associations,name|max:32',
                    'group_leader_id' => 'required|integer|exists:users,id'
                ];
            case 'update':
                return [
                    'name' => 'required|max:32|unique:group_associations,name,'.$this->id,
                    'group_leader_id' => 'required|integer|exists:users,id'
                ];
            default:
                {
                    return [];
                }
        }
    }
}
