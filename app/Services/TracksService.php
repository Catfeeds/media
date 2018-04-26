<?php

namespace App\Services;

use App\Handler\Common;
use App\Models\Custom;
use App\User;

class TracksService
{
    /**
     * 说明: 添加跟进前相关数据
     *
     * @return mixed
     * @author 罗振
     */
    public function relevantData()
    {
        // 客户
        $relevantData['customs'] = Custom::all()->map(function($custom) {
            return [
                'label' => $custom->name,
                'value' => $custom->id,
            ];
        });

        return $relevantData;
    }

    /**
     * 说明:获取当前登录用户同事信息
     *
     * @return mixed
     * @author 刘坤涛
     */
    public function getColleagueInfo()
    {
        $user = Common::user();
        return User::where('ascription_store' , $user->ascription_store)->where('id','!=',$user->id)->get();
    }
}