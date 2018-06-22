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
        $res = User::where('level', 2)->orWhere('level', 3)->get();
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
        //获取等级
        $level = User::where('id', $id)->value('level');
        switch ($level) {
            case 3:
                $storefront_id = Storefront::where('user_id', $id)->value('id');
                $res = User::where(['ascription_store' => $storefront_id, 'level' => 4])->get();
                break;
            case 2:
                $storefrontsId = Storefront::where('area_manager_id', $id)->pluck('id')->toArray();
                $res = User::whereIn('ascription_store', $storefrontsId)->where('level',4)->orWhere('level',5)->get();
                break;
        }
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
            if ($v->demand == 1) {
                if ($v->house) $v->status = $v->staffUser->real_name.'已录入系统,房源编号'.$v->house->house_identifier;
            }
            if ($v->demand == 2) {
                if ($v->custom) $v->status = $v->staffUser->real_name.'已录入系统,客源编号'.$v->custom->id;
            }

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

    //通过用户id查询电话,获取该用户微信openid
    public function getOpenid($id)
    {
        $tel = User::where('id', $id)->value('tel');
        $data = curl(config('setting.clw_url').'/api/admin/get_openid_by_tel?tel='.$tel,'get');
        return $data->data;
    }

    //发送微信消息
    public function send($openid, $name, $tel, $staff = false)
    {
        $data['openid'] = json_encode(array($openid));
        $data['name'] = $name;
        $data['tel'] = $tel;
        $data['staff'] = $staff;
        curl(config('setting.wechat_url').'/new_custom_notice','post', $data);
    }

    //通过电话获取用户id
    public function getId($tel)
    {
        return User::where('tel', $tel)->value('id');
    }



}