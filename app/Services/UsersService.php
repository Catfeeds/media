<?php

namespace App\Services;

use App\Handler\Common;
use App\Models\Storefront;

class UsersService
{
    /**
     * 说明: 获取门店信息
     *
     * @param $request
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
            return Storefront::where('user_id', null)->pluck('storefront_name', 'id');
        } elseif ($user->level == 1 && $request->level == 4) {
            return Storefront::where([])->pluck('storefront_name', 'id');
        } elseif ($user->level == 2 && $request->level == 3) {
            return Storefront::where([
                'area_manager_id' => $user->id,
                'user_id' => null
            ])->pluck('storefront_name', 'id');
        } elseif ($user->level == 2 && $request->level == 4) {
            return Storefront::where('area_manager_id', $user->id)->pluck('storefront_name', 'id');
        } elseif ($user->level == 3 && $request->level == 4) {
            return Storefront::where('user_id', $user->id)->pluck('storefront_name', 'id');
        }
    }
}