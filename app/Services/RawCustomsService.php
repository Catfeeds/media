<?php

namespace App\Services;

use App\Handler\Common;
use App\Models\Custom;
use App\Models\OfficeBuildingHouse;
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
    public function getStaff($id)
    {

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
            if ($v->demand == 1) {
                $data = OfficeBuildingHouse::where('gd_identifier', $v->identifier)->first();
            } else {
                $data = Custom::where('identifier', $v->identifier)->first();
            }
            $v->entry = !empty($data)?true:false;
        }
        return $item;
    }

    //通过用户id查询电话,获取该用户微信openid
    public function getOpenid($id)
    {
        $tel = User::where('id', $id)->value('tel');
        $openid = curl(config('setting.we_url').'?tel='.$tel,'get');
        return $openid;
    }

    //发送微信消息
    public function send($openid, $name, $tel, $level)
    {
        $data['openid'] = json_encode(array($openid));
        $data['name'] = $name;
        $data['tel'] = $tel;
        $data['level'] = $level;
        curl(config('setting.wechat_url').'/new_custom_notice','post', $data);
    }

    public function getId($tel)
    {
        return User::where('tel', $tel)->value('id');
    }



}