<?php

namespace App\Http\Requests\API;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OwnerViewRecordsRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'user_id' => [
                        'required',
                        'integer',
                        Rule::in(
                            User::all()->pluck('id')->toArray()
                        )
                    ],
                    'house_model' => 'required',
                    'house_id' => [
                        'required',
                        'integer',
                        Rule::in(
                            $this->house_model::make()::all()->pluck('id')->toArray()
                        )
                    ],
                ];
            default:
                {
                    return [];
                }
        }
    }
}
