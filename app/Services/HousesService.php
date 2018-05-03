<?php

namespace App\Services;

use App\Models\BuildingBlock;
use App\Models\House;
use App\Models\OwnerViewRecord;

class HousesService
{
    /**
     * 说明: 房源编号
     *
     * @param $initials
     * @param $houseId
     * @return string
     * @author 罗振
     */
    public function setHouseIdentifier($initials, $houseId)
    {
        if (strlen($houseId) == 1) {
            return $initials.date('Ymd', time()).'00'.$houseId;
        } elseif (strlen($houseId) == 2) {
            return $initials.date('Ymd', time()).'0'.$houseId;
        } else {
            return $initials.date('Ymd', time()).$houseId;
        }
    }

    /**
     * 说明: 通过楼座获取城市
     *
     * @param $BuildingBlockId
     * @return array
     * @author 罗振
     */
    public function adoptBuildingBlockGetCity($BuildingBlockId)
    {
        $temp = BuildingBlock::find($BuildingBlockId);

        // 拼接商圈获取城市数据
        $arr[] = $temp->building->area->city->id;
        $arr[] = $temp->building->area->id;
        $arr[] = $temp->building->id;
        $arr[] = $BuildingBlockId;

        return $arr;
    }

    /**
     * 说明：通过表格楼栋分割成标准数据
     *
     * @param $str
     * @return array
     * @author jacklin
     */
    public function blockForm($str)
    {
        $data = array();
        // 如果有 '栋'
        $dong = mb_strstr($str, '栋', true);
        if (!empty($dong)) {
            $data['name'] = $dong;
            $data['name_unit'] = '栋';
            $str = explode('栋', $str)[1];
        }

        $zuo = mb_strstr($str, '座', true);
        if (!empty($zuo)) {
            $data['name'] = $zuo;
            $data['name_unit'] = '座';
            $str = explode('座', $str)[1];
        }

        $danyuan = mb_strstr($str, '单元', true);
        if (!empty($danyuan)) {
            $data['unit'] = $danyuan;
            $data['unit_unit'] = '单元';
        } else {
            $danyuan = mb_strstr($str, '门', true);
            if (!empty($danyuan)) {
                $data['unit'] = $danyuan;
                $data['unit_unit'] = '门';
            }
        }
        if (empty($data)) return array('name' => '独', 'name_unit' => '栋');
        return $data;
    }



    public function houseNumValidate($request)
    {
//        $string = '711-712'; // 房号
//        $string = 'F'; // 房号
        $floor = '71';   // 楼层
        $block = 13;
//        $model = '\App\Models\DwellingHouse';
        // 处理房号
        $temp = explode('-', $request->house_number);
//        dd($temp);

        // 判断是否为整层
        if (count($temp) > 1) {
            // 包含英文验证
            $preg2= '/[A-Za-z]/';
            if(preg_match($preg2, $request->house_number)){
                return false;
            }

            foreach ($temp as $v) {
                // 判断楼层
                $temp2 = strpos($v, $request->floor);
                // 判断楼层是否正确(肯定是从第0位开始)
                if ($temp2 !== 0) {
                    return false;
                }
            }
        } else {
            // 判断是否包含F
            if ($request->house_number !== 'F') {
                return false;
            }
        }

        // 判断楼座中否是有这个房号
        $temp3 = $request->model::where([
            'building_block_id' => $request->building_block_id,
            'house_number' => $v,
            'floor' => $request->floor
        ])->first();
        if ($temp3) {
            return false;
        }

        return true;
    }


    /**
     * 说明:获取房源信息
     *
     * @param $request
     * @return mixed
     * @author 刘坤涛
     */
    public function getHouse($request)
    {
        switch ($request->house_model) {
            case '1':
                $model = "App\\Models\\DwellingHouse";
                break;
            case '2':
                $model = "App\\Models\\OfficeBuildingHouse";
                break;
            case '3':
                $model = "App\\Models\\ShopsHouse";
                break;
            default;
                break;
        }
        return  $model::find($request->house_id);
    }

    /**
     * 说明:获取查看记录
     *
     * @param $house
     * @param null $per_page
     * @return mixed
     * @author 刘坤涛
     */
    public function getViewRecord($house, $per_page = null)
    {
        $model = get_class($house);
        return  OwnerViewRecord::where(['house_id' => $house->id, 'house_model' => $model])->paginate($per_page);
    }
}