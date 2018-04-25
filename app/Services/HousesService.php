<?php

namespace App\Services;

use App\Handler\Common;
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
        // 总经理
        if ($user->level ==1 ) {
            return [];
        }
        if($user->level == 2) {
            // 获取区域经理下面的门店
            $storefront = Storefront::where('area_manager_id', $user->id)->pluck('id')->toArray();
            // 获取门店下所有房源
            $allHouseId = DwellingHouse::whereIn('storefront', $storefront)->pluck('id')->toArray();

        } elseif($user->level == 3) {
            $dwellingHouseId = array();

            // 查询门店的所有房源
            // 判断是否属于店长
            $storefront = Storefront::where('user_id', $user->id)->first();
            if (!empty($storefront)) {
                // 获取门店下所有店员id
                $users = User::where('ascription_store', $storefront->id)->pluck('id')->toArray();

                if (!empty($users)) {
                    // 获取房源id
                    $dwellingHouseId = DwellingHouse::whereIn('guardian', $users)->pluck('id')->toArray();
                }
                // 店内公盘
                $storefrontHouse = DwellingHouse::where('storefront', $storefront->id)->pluck('id')->toArray();

                $allHouseId = array_merge($dwellingHouseId, $storefrontHouse);
            } else {
                $allHouseId = array();
            }

        } elseif($user->level == 4) {
            // 获取公盘数据
            $publicHouseId = DwellingHouse::where('storefront', $user->ascription_store)->pluck('id')->toArray();

            // 获取业务员自己的房源
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

    /**
     * 说明:根据用户登录等级选择公私盘类型获取对应的门店
     *
     * @param $request
     * @return array
     * @author 刘坤涛
     */
    public function public_private_info($public_private)
    {
        $user = Common::user();
        $item = ['guardian' => null, 'storefront' => null];
        if($user->level == 1 && $public_private == 3) {
            $item['guardian'] = $user->id;
        }  elseif ($user->level == 2 && $public_private == 2) {
            $item['storefront'] = Storefront::where('area_manager_id',$user->id)->pluck('id')->toArray();
        } elseif ($user->level == 2 && $public_private == 3) {
            $item['guardian'] = $user->id;
            $item['storefront'] = Storefront::where('area_manager_id',$user->id)->pluck('id')->toArray();
        } elseif ($user->level == 3 && $public_private == 2) {
            $item['storefront'] = Storefront::where('user_id', $user->id)->pluck('id')->toArray();
        } elseif ($user->level == 3 && $public_private == 3) {
            $item['guardian'] = $user->id;
            $item['storefront'] = Storefront::where('user_id', $user->id)->pluck('id')->toArray();
            } elseif ($user->level == 4 && $public_private == 2) {
            $item['storefront'] = Storefront::where('id',$user->ascription_store)->pluck('id')->toArray();
        } elseif($user->level == 4 && $public_private == 3) {
            $item['guardian'] = $user->id;
            $item['storefront'] = Storefront::where('id', $user->ascription_store)->pluck('id')->toArray();
        }
//        $res = $item['storefront']->map(function($v) {
//            return [
//                'id' => $v['id']
//            ];
//        });

//        $item['storefront'] = $res;
            return $item;
    }
}