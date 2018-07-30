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
        $res = User::where('openid', '!=', null)->whereIn('level',[1, 2, 3])->get();
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
                $res = User::where(['ascription_store' => $storefront_id, 'level' => 4])
                            ->where('openid', '!=', null)->get();
                break;
            case 2:
                $storefrontsId = Storefront::where('area_manager_id', $id)->pluck('id')->toArray();
                $res = User::whereIn('ascription_store', $storefrontsId)
                            ->whereIn('level',  [4, 5])
                            ->where('openid', '!=', null)->get();
                break;
            case 1:
                //总经理  查询所有
                $res = User::where('openid', '!=' , null)->get();
                break;
                default;
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
            if (!$v->shopkeeper_deal && !empty($v->shopkeeperUser)) $v->status = '已发送给组长'.'('.$v->shopkeeperUser->real_name.')';
            if ($v->staff_id && !empty($v->shopkeeperUser)) $v->status = '组长'.'('.$v->shopkeeperUser->real_name.')'.'已收到转交给'.$v->staffUser->real_name;
            if ($v->staff_deal) $v->status = $v->staffUser->real_name.'已收到';
            if (!empty($v->house)) $v->status = $v->staffUser->real_name.'已录入系统,房源编号'.$v->house->house_identifier;
            if (!empty($v->custom)) $v->status = $v->staffUser->real_name.'已录入系统,客源编号'.$v->custom->id;
        }
        return $item;
    }

    //店长处理页面信息
    public function getInfo($item)
    {
        foreach ($item as $v) {
            $v->staff = $v->staffUser->real_name;
            $staff_deal = RawCustom::where('id', $v->id)->value('staff_deal');
            if (!$staff_deal) {
                $v->determine = 1; //为确定
            } else {
                $v->determine = 2; //已确定
            }
            if ($v->feedback) $v->determine = 3; //已反馈
        }
        return $item;
    }

    //是否录入系统
    public function getStaffInfo($item)
    {
        foreach ($item as $v) {
            if (!empty($v->custom)) $v->entry = true;
            if (!empty($v->house)) $v->entry = true;
        }
        return $item;
    }

    //通过用户id查询电话,获取该用户微信openid
    public function getOpenid($id)
    {
        $openid = User::where('id', $id)->value('openid');
        return $openid;
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

    // 获取转换率
    public function getConversionRate(
        $model
    )
    {
        $rawCustoms = $model->get();

        $count = 0;
        foreach ($rawCustoms as $v) {
            if (!empty($v->custom)) $count++;
            if (!empty($v->house)) $count++;
        }

        // 返回百分比
        if (!empty($count) && !empty($rawCustoms->count())) {
            $res = round($count / $rawCustoms->count(),3) * 100 . '%';
            return $res;
        } else {
            return '0%';
        }
    }

}