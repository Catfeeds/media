<?php

namespace App\Services;

use App\Exceptions\Handler;
use App\Handler\Common;
use App\Models\Custom;
use App\Models\GroupAssociation;
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
    public function addManager(
        $data
    )
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
     * 说明: 获取本店可以指定的组长id
     *
     * @param $request
     * @return mixed
     * @author 罗振
     */
    public function getGroupLeader(
        $request
    )
    {
        // 获取所属店
        if (!empty($request->id)) {
            $user = User::find($request->id);
        } else {
            $user = Common::user();
        }

        // 获取已经指定组长
        $groupAssociation = GroupAssociation::where(['storefronts_id' => $user->storefront->id])->pluck('group_leader_id')->toArray();

        if (!empty($request->id)) {
            unset($groupAssociation[array_search($user->id,$groupAssociation)]);
        }

        // 获取用户表组长
        $groupLeader = User::where(['level' => 5, 'ascription_store' => $user->storefront->id])->whereNotIn('id', $groupAssociation)->get();

        return $groupLeader;
    }

    /**
     * 说明: 通过门店获取组信息
     *
     * @param $request
     * @return mixed
     * @author 罗振
     */
    public function adoptStorefrontsGetGroup(
        $request
    )
    {
        if (!empty($request->storefronts_id)) {
            $storefrontsId = $request->storefronts_id;
        } else {
            $user = Common::user();
            $storefrontsId = $user->storefront->id;
        }

        return GroupAssociation::where(['storefronts_id' => $storefrontsId])->get();
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

    //获取工单列表相关信息
    public function getGdInfo($item)
    {
        foreach($item as $v) {
            $v->real_name = Common::user()->real_name;
            if (!$v->shopkeeper_deal) $v->status = '已发送给组长'.'('.$v->shopkeeperUser->real_name.')';
            if ($v->staff_id) $v->status = '组长'.'('.$v->shopkeeperUser->real_name.')'.'已收到转交给'.$v->staffUser->real_name;
            if ($v->staff_deal) $v->status = $v->staffUser->real_name.'已收到';

        }

        return $item;
    }


}