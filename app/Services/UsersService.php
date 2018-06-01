<?php

namespace App\Services;

use App\Exceptions\Handler;
use App\Handler\Common;
use App\Models\Custom;
use App\Models\OfficeBuildingHouse;
use App\Models\Storefront;
use App\Models\Track;
use App\User;
use Illuminate\Support\Facades\Auth;
use Qiniu\Http\Request;

class UsersService
{
    /**
     * 说明: 获取门店信息
     *
     * @param $request
     * @return bool
     * @author 罗振
     */
    public function getInfo(
        $request
    )
    {
        $user = Common::user();
        if ($user->level == 1 && $request->level == 2) {
            return ;
        } elseif ($user->level == 1 && $request->level == 3) {
            return Storefront::where('user_id', null)->get();
        } elseif ($user->level == 1 && $request->level == 4) {
            return Storefront::where([])->get();
        } elseif ($user->level == 2 && $request->level == 3) {
            return Storefront::where([
                'area_manager_id' => $user->id,
                'user_id' => null
            ])->get();
        } elseif ($user->level == 2 && $request->level == 4) {
            return Storefront::where('area_manager_id', $user->id)->get();
        } elseif ($user->level == 3 && $request->level == 4) {
            return Storefront::where('user_id', $user->id)->get();
        }
    }

    /**
     * 说明: 添加总经理
     *
     * @param $data
     * @return bool
     * @author 罗振
     */
    public function addManager($data)
    {
        \DB::beginTransaction();
        try {
            $user = User::create([
                'tel' => $data['tel'],
                'real_name' => $data['real_name'],
                'level' => 1,
                'password' => bcrypt($data['password']),
            ]);
            if (!$user) {
                throw new \Exception('生成总经理失败');
            }

            $user->assignRole(1);
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::error($exception->getMessage());
            return false;
        }
    }

    /**
     * 说明:业务统计
     *
     * @param $request
     * @return array
     * @author 李振
     */
    // TODO 统计逻辑
    public function businessStatistics
    (
        $request
    )
    {
        $user = Auth::guard('api')->user();
        $date = Common::getTime(time());
        $new_house = OfficeBuildingHouse::where([
            ['guardian'.$user->id],
            ['created','>',$date],
            ])->get();  //新增房源
        $new_passenger_source = Custom::where([
            ['guardian'=>$user->id ],
            ['created','>',$date],
        ])->get();  //新增客源
        $house_follow_up = Track::where([
            ['house_model'=>'App\Models\OfficeBuildingHouse'],
            ['user_id'=>$user->id],
            ['created','>',$date],
        ])->get();  //房源跟进
        $customer_follow_up = Track::where([
            ['house_model'=>' '],
            ['user_id'=>$user->id],
            ['created','>',$date],
        ])->get();  //客源跟进
        $room_source = Track::where([
            ['house_model'=>'App\Models\OfficeBuildingHouse'],
            ['tracks_mode'=>7],
            ['user_id'=>$user->id],
            ['created','>',$date],
        ])->get();  //房源带看
        $passenger_source = Track::where([
            ['house_model'=>' '],
            ['tracks_mode'=>7],
            ['user_id'=>$user->id],
            ['created','>',$date],
        ])->get(); //客源带看
        return [$new_house , $new_passenger_source , $house_follow_up , $customer_follow_up , $room_source , $passenger_source];
    }
}