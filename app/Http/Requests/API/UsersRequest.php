<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

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
                    'real_name' => 'required|max:32',
                    'nick_name' => 'required|max:32',
                    'ascription_store' => 'required|array',
                    'level' => 'required|integer|between:1,4',
                    'tel' => 'required|max:16',
                    'remark' => 'nullable|max:255',
                ];
            case 'update':
                return [
                    'real_name' => 'required|max:32',
                    'nick_name' => 'required|max:32',
                    'ascription_store' => 'required|array',
                    'level' => 'required|integer|between:1,4',
                    'remark' => 'nullable|max:255',
                ];
            case 'updatePassword':
                return [
                    'password' => 'required|min:6|max:18',
                ];
            case 'updateTel':
                return [
                    'tel' => 'required|max:16',
                ];
            default:
                {
                    return [];
                }
        }
    }
}
