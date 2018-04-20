<?php

namespace App\Http\Requests\API;

use App\Models\Custom;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TracksRequest extends FormRequest
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

                ];
            case 'PUT':
            case 'PATCH':
                {
                    return [

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
     * @author 罗振
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'custom_id' => [
                        'required',
                        'integer',
//                        Rule::in(
//                            Custom::all()->pluck('id')->toArray()
//                        )
                    ],
                    'conscientious_id' => [
                        'nullable',
                        'integer',
                        // 用户必须存在
                    ],
                    'tracks_mode' => 'nullable|integer',
                    'content' => 'max:65535'
                ];
            case 'PUT':
            case 'PATCH':
                {
                    return [

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
