<?php

namespace App\Http\Requests\API;

use App\Models\BuildingBlock;
use App\Models\OfficeBuildingHouse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OfficeBuildingHousesRequest extends FormRequest
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
          switch ($this->route()->getActionMethod()) {
              case 'updateOfficeBusinessState':
                  return [
                      'id' => [
                          'required',
                          'integer',
                          Rule::in(
                              OfficeBuildingHouse::all()->pluck('id')->toArray()
                          )
                      ],
                      'house_busine_state' => 'required|integer|between:1,6',
                  ];
              case 'store':
                  return [
                      // 核心信息
                      'building_block_id' => [
                          'required',
                          'integer',
    //                        Rule::in(
    //                            BuildingBlock::all()->pluck('id')->toArray()
    //                        )
                      ],
                      'house_number' => 'required|max:32',
                      'owner_info' => 'required',
                      // 房子信息
                      'room' => 'nullable|numeric|max:9999999999',
                      'hall' => 'nullable|numeric|max:9999999999',
                      'constru_acreage' => 'required|max:32',
                      'split' => 'nullable|integer|between:1,2',
                      'min_acreage' => 'nullable|numeric|max:99999999999',
                      'floor' => 'nullable|numeric|max:99999999999',
                      'station_number' => 'max:32',
                      'office_building_type' => 'nullable|integer|between:1,5',
                      'register_company' => 'nullable|integer|between:1,2',
                      'open_bill' => 'nullable|integer|between:1,2',
                      'renovation' => 'nullable|integer|between:1,5',
                      'orientation' => 'nullable|integer|between:1,9',
                      'house_description' => 'max:255',
                      // 租赁信息
                      'rent_price' => 'required|numeric|max:9999999999',
                      'rent_price_unit' => 'nullable|integer|between:1,2',
                      'payment_type' => 'required|integer|between:1,12',
                      'check_in_time' => 'nullable|date',
                      'shortest_lease' => 'nullable|integer|between:1,5',
                      'rent_free' => 'nullable|integer|between:1,11',
                      'increasing_situation' => 'max:32',
                      // 业务信息
                      'house_busine_state' => 'required|integer|between:1,6',
                      'pay_commission' => 'nullable|numeric|max:9999999999',
                      'pay_commission_unit' => 'nullable|integer|between:1,2',
                      'prospecting' => 'nullable|integer|between:1,2',
                      'source' => 'nullable|integer|between:1,7',
                      'house_key' => 'max:32',
                      'see_house_time' => 'nullable|integer|between:1,3',
                      'see_house_time_remark' => 'max:32',
                      'certificate_type' => 'nullable|integer|between:1,7',
                      'house_proxy_type' => 'nullable|integer|between:1,2',
                      // 房源照片
                      'house_type_img' => 'max:1024',
                      'indoor_img' => 'max:1024',
                  ];
              case 'update':
                  return [
                      // 核心信息
                      'building_block_id' => [
                          'integer',
    //                            Rule::in(
    //                                BuildingBlock::all()->pluck('id')->toArray()
    //                            )
                      ],
                      'house_number' => 'max:32',
                      // 房子信息
                      'room' => 'nullable|numeric|max:9999999999',
                      'hall' => 'nullable|numeric|max:9999999999',
                      'constru_acreage' => 'max:32',
                      'split' => 'nullable|integer|between:1,2',
                      'min_acreage' => 'nullable|numeric|max:99999999999',
                      'floor' => 'nullable|numeric|max:99999999999',
                      'station_number' => 'max:32',
                      'office_building_type' => 'nullable|integer|between:1,5',
                      'register_company' => 'nullable|integer|between:1,2',
                      'open_bill' => 'nullable|integer|between:1,2',
                      'renovation' => 'nullable|integer|between:1,5',
                      'orientation' => 'nullable|integer|between:1,9',
                      'house_description' => 'max:255',
                      // 租赁信息
                      'rent_price' => 'nullable|numeric|max:9999999999',
                      'rent_price_unit' => 'nullable|integer|between:1,2',
                      'payment_type' => 'nullable|integer|between:1,12',
                      'check_in_time' => 'nullable|date',
                      'shortest_lease' => 'nullable|integer|between:1,5',
                      'rent_free' => 'nullable|integer|between:1,11',
                      'increasing_situation' => 'max:32',
                      // 业务信息
                      'house_busine_state' => 'required|integer|between:1,6',
                      'pay_commission' => 'nullable|numeric|max:9999999999',
                      'pay_commission_unit' => 'nullable|integer|between:1,2',
                      'prospecting' => 'nullable|integer|between:1,2',
                      'source' => 'nullable|integer|between:1,7',
                      'house_key' => 'max:32',
                      'see_house_time' => 'nullable|integer|between:1,3',
                      'see_house_time_remark' => 'max:32',
                      'certificate_type' => 'nullable|integer|between:1,7',
                      'house_proxy_type' => 'nullable|integer|between:1,2',
                      // 房源照片
                      'house_type_img' => 'max:1024',
                      'indoor_img' => 'max:1024',
                  ];
              default:
                  {
                      return [];
                  }
          }
    }
}
