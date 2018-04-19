<?php

namespace App\Services;

use App\Models\Custom;
use App\User;
use Illuminate\Support\Facades\Auth;

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
        $relevantData['customs'] = Custom::all()->pluck('name', 'id')->toArray();

        $relevantData['user'] = User::all()->pluck('name', 'id')->toArray();

//        $relevantData['track'] = Auth::guard('api')->user()->name;
        $relevantData['track'] = 'zxz';

        return $relevantData;
    }
    
}