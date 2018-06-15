<?php

namespace App\Services;

use App\Handler\Common;
use App\Models\Custom;
use App\Models\RawCustom;
use App\Models\Storefront;
use App\User;

class RawCustomsService
{

    private $user;

    public function __construct()
    {
        $this->user = Common::user();
    }


    //获取店长名称
    public function getShopkeeper()
    {
        $id = Storefront::pluck('user_id')->toArray();
        $res = User::whereIn('id', $id)->get();
        return $res->map(function ($v) {
            return [
                'label' => $v->real_name,
                'value' => $v->id
            ];
        });
    }

    //获取店长下属业务员信息
    public function getStaff()
    {
        $id = 3;
        //获取门店id
        $storefront_id = Storefront::where('user_id', $id)->value('id');
        //根据门店id获取所有人员
        $res = User::where('ascription_store', $storefront_id)->where('id', '!=', $id)->get();
        return $res->map(function($v) {
            return [
                'label' => $v->real_name,
                'value' => $v->id
            ];
        });

    }

    //获取工单列表相关信息
    public function getGdInfo($item)
    {
        foreach($item as $v) {
//            $v->real_name = $this->user->real_name;
            $v->real_name = '浩哥';
            if (!$v->shopkeeper_deal) $v->status = '已发送给组长'.'('.$v->shopkeeperUser->real_name.')';
            if ($v->staff_id) $v->status = '组长'.'('.$v->shopkeeperUser->real_name.')'.'已收到转交给'.$v->staffUser->real_name;
            if ($v->staff_deal) $v->status = $v->staffUser->real_name.'已收到';
        }
        return $item;
    }

    //店长处理页面信息
    public function getInfo($item)
    {
        foreach ($item as $v) {
            $v->staff = $v->staffUser->real_name;
            $staff_deal = RawCustom::where('id', $v->id)->value('staff_deal');
            if ($staff_deal) {
                $v->determine = true;
            } else {
                $v->determine = false;
            }
        }
        return $item;
    }

    //是否录入系统
    public function getStaffInfo($item)
    {
        foreach ($item as $v) {
            $entry = Custom::where('identifier', $v->identifier)->first();
            if ($entry) {
                $v->entry = true;
            } else {
                $v->entry = false;
            }
        }
        return $item;
    }




}