<?php

namespace App\Services;

use App\Models\BuildingBlock;

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
        $string = 'F701-7F'; // 房号
        $floor = '7';   // 楼层
        $block = 13;
        $model = '\App\Models\DwellingHouse';
        // 处理房号
        $temp = explode('-', $string);
//        dd($temp);

        // 判断是否为整层
        if (count($temp) > 1) {
            $temp5 = strpos($string, 'F');
            if ($temp5) {
                dd(123);
                return false;
            }

        }


        foreach ($temp as $v) {
            // 判断楼层
            $temp2 = strpos($v, $floor);
            // 楼层正确返回0(第一位)
            if (empty($temp2)) {
                // 英文验证规则
                $preg2 = '/[A-Za-z]*/';
                $string1 = '2sF';
                if(preg_match($preg2,$string1)){
                    // 包含英文
                    $temp4 = strpos($string1, 'F');
                    if ($temp4) {



                        return false;
                    }
                }

                return false;
            }


            // 判断楼座中否是有这个房号
            $temp3 = $model::where([
                'building_block_id' => $block,
                'house_number' => $v,
            ])->first();
            if ($temp3) {
                return false;
            }
        }






    }
}