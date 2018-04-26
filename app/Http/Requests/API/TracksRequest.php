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
        switch ($this->route()->getActionMethod()) {
            case 'store':
                return [
                    'house_model' => 'required|integer',
                    'house_id' => 'required|integer',
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
            case 'addCustomsTracks':
                return [
                    'custom_id' => [
                        'required',
                        'integer',
                        Rule::in(
                            Custom::all()->pluck('id')->toArray()
                        )
                    ],
                    'tracks_mode' => 'required|integer',
                    'content' => 'required',
                    'house_model' => 'nullable|integer|between:1,3',
                    'house_id' => 'nullable|integer',
                ];
            default:
                {
                    return [];
                }
        }
    }
}
