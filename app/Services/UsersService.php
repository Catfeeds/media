<?php

namespace App\Services;

use App\Handler\Common;
use App\Models\Storefront;
use App\User;

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
                'nick_name' => $data['real_name'],
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
}