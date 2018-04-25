<?php

namespace App\Services;

use App\Models\BuildingBlock;
use App\Models\DwellingHouse;
use App\Models\Storefront;
use App\User;

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
     * 说明: 获取当前角色能查看房源id
     *
     * @param $user
     * @return array
     * @author 罗振
     */
    public function getCanSeeHouseId($user)
    {
        // 获取所有房源
        $allDwellingHouse = DwellingHouse::all()->pluck('storefront', 'id');

        // 总经理
        if ($user->level ==1 ) {
            return [];
        }
        if($user->level == 2) {
            // 获取区域经理下面的门店
            $storefront = Storefront::where('area_manager_id', $user->id)->pluck('id')->toArray();

            $allHouseId = array();
            foreach ($allDwellingHouse as $k => $v) {
                if (!empty($v)) {
                    foreach ($v as $val) {
                        if (in_array($val, $storefront)) {
                            $allHouseId[] = $k;
                        }
                    }
                }
            }
            // 去重
            $allHouseId = array_unique($allHouseId);
        } elseif($user->level == 3) {
            $dwellingHouseId = array();

            // 查询门店的所有房源
            // 判断是否属于店长
            $storefrontId = Storefront::where('user_id', $user->id)->pluck('id')->toArray();

            if (!empty($storefrontId)) {
                // 获取门店下所有店员id
                $users = User::where('ascription_store', $storefrontId)->pluck('id')->toArray();

                if (!empty($users)) {
                    // 私盘
                    $dwellingHouseId = DwellingHouse::whereIn('guardian', $users)->pluck('id')->toArray();
                }

                $storefrontHouse = array();
                foreach ($allDwellingHouse as $k => $v) {
                    if (!empty($v)) {
                        foreach ($v as $val) {
                            if (in_array($val, $storefrontId)) {
                                $storefrontHouse[] = $k;
                            }
                        }
                    }
                }
                $storefrontHouse = array_unique($storefrontHouse);

                $allHouseId = array_merge($dwellingHouseId, $storefrontHouse);
            } else {
                $allHouseId = array();
            }

        } elseif($user->level == 4) {
            // 店内公盘
            $publicHouseId = array();
            foreach ($allDwellingHouse as $k => $v) {
                if (!empty($v)) {
                    foreach ($v as $val) {
                        if ($val == $user->ascription_store) {
                            $publicHouseId[] = $k;
                        }
                    }
                }
            }
            $publicHouseId = array_unique($publicHouseId);

            // 私盘
            $dwellingHouseId = DwellingHouse::where('guardian', $user->id)->pluck('id')->toArray();

            $allHouseId = array_merge($publicHouseId, $dwellingHouseId);
        }

        // 查询所有公盘
        $publicHouseId = DwellingHouse::where([
            'storefront' => null,
            'guardian' => null
        ])->pluck('id')
        ->toArray();

        return array_merge($publicHouseId, $allHouseId);
    }
}