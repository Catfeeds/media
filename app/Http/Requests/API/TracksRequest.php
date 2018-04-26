<?php

namespace App\Http\Requests\API;

use App\Models\Custom;
use App\User;
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
                    'house_model' => 'required|integer',
                    'house_id' => [
                        'required',
                        'integer',
                    ],
                    'user_id' => [
                        'required',
                        'integer',
                        Rule::in(
                            User::all()->pluck('id')->toArray()
                        )
                    ],
                    'custom_id' => [
                        'required',
                        'integer',
                        Rule::in(
                            Custom::all()->pluck('id')->toArray()
                        )
                    ],
                    'tracks_mode' => 'required|integer',
                    'content' => 'required',
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
