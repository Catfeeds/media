<?php

namespace App\Services;


use App\Models\Storefront;
use Illuminate\Support\Facades\Auth;

class UsersService
{
    public function getInfo()
    {
        $item = array();
        //获取当前登录用户的等级
        $current_level = Auth::guard('api')->user()->level;
        switch ($current_level) {
            case '1' :
                $item['level_name'] = '区域经理';
                $item['level']= 2;
                break;
            case '2':
                $item['level_name'] = '门店店长';
                $item['level']= 3;
                break;
            case  '3':
                $item['level_name'] = '业务员';
                $item['level']= 4;
                break;
            default:
                break;
        }
        //获取所有未归属的门店Id和对应门店名称
        $stroefromts = Storefront::where('user_id',null)->get(['id','storefront_name'])->toArray();
        $item['storefront'] = $stroefromts;
        return $item;
   }
}