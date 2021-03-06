<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

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
                    'permissions.*.in' => '请勿添加不存在的权限'
                ];
            case 'update':
                return [

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
                    'name_cn' => 'required|max:255',
                    'name_en' => 'required|max:255|regex:/^[a-z\d\_]*$/i|unique:roles,name',
                ];
            case 'update':
                return [
                ];
            default;
                return [

                ];
        }
    }

}
