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
        if ($user->level == 1 && $request->level == 3) {
            // 获取未绑定的门店
            return Storefront::where('user_id', null)->get();
        } elseif ($user->level == 1 && ($request->level == 4 || $request->level == 5)) {
            // 获取所有门店
            return Storefront::where([])->get();
        } elseif ($user->level == 2 && $request->level == 3) {
            // 获取指定区域经理下面的未绑定门店
            return Storefront::where([
                'area_manager_id' => $user->id,
                'user_id' => null
            ])->get();
        } elseif ($user->level == 2 && ($request->level == 4 || $request->level == 5)) {
            // 获取指定区域经理下面的门店
            return Storefront::where('area_manager_id', $user->id)->get();
        } else {
            return collect();
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
     * 说明: 获取下级信息
     *
     * @return array
     * @author 罗振
     */
    public function getSubordinateUser()
    {
        // 成员等级
        $data = [
            [
                'value' => 2,
                'label' => '区域经理'
            ],
            [
                'value' => 3,
                'label' => '商圈经理'
            ],
            [
                'value' => 6,
                'label' => '店秘'
            ],
            [
                'value' => 5,
                'label' => '门店经理'
            ],
            [
                'value' => 4,
                'label' => '业务经理'
            ],
        ];

        $user = Common::user();

        if ($user->level == 1) {
            unset($data[2]);
            return $data;
        } elseif ($user->level == 2) {
            unset($data[0]);
            return $data;
        } elseif ($user->level == 3 || $user->level == 6) {
            unset($data[0]);
            unset($data[1]);
            return $data;
        }
    }

    /**
     * 说明: 获取门店及门店经理信息
     *
     * @return array
     * @author 罗振
     */
    public function getGroupAndStorefronts()
    {
        $user = Common::user();

        if ($user->level == 2) {
            $storefronts = Storefront::where(['area_manager_id' => $user->id])->get();
        } elseif ($user->level == 3) {
            $storefronts = Storefront::where(['user_id' => $user->id])->get();
        } elseif ($user->level == 1) {
            $storefronts = Storefront::where([])->get();
        } elseif ($user->level == 6) {
            $storefronts = Storefront::where('id', $user->ascription_store)->get();
        } else {
            $storefronts = collect();
        }

        $data = array();
        foreach ($storefronts as $k => $v) {
            $data[$k]['value'] = $v->id;
            $data[$k]['label'] = $v->storefront_name;
            $groups = User::where([
                'ascription_store' => $v->id,
                'level' => 5
            ])->get();
            $children = array();
            foreach ($groups as $key => $val) {
                $children[$key]['value'] = $val->id;
                $children[$key]['label'] = $val->real_name;
            }

            $data[$k]['children'] = $children;
        }

        return $data;
    }
}