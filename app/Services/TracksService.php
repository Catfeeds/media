<?php

namespace App\Services;

use App\Handler\Common;
use App\Models\Custom;
use App\Repositories\CustomRepository;
use App\User;

class TracksService
{
    /**
     * 说明:获取添加房源跟进时的客户下拉数据
     *
     * @param CustomRepository $repository
     * @return mixed
     * @author 罗震
     */
    public function relevantData(CustomRepository $repository)
    {
        // 客户
        $res = $repository->getList()->get();
        return $this->selectForm($res);
    }

    /**
     * 说明:客户数据下拉格式
     *
     * @param $data
     * @return mixed
     * @author 刘坤涛
     */
    public function selectForm($data)
    {
        return  $data->map(function($custom) {
            return [
                'label' => $custom->name,
                'value' => $custom->id,
            ];
        });
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