<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class PermissionsService
{
    /**
     * 说明: 获取用户权限
     *
     * @param $permission
     * @return bool
     * @author 罗振
     */
    public function getUserPermission(
        $permission
    )
    {
        // 获取用户角色
        $user = Auth::guard('api')->user();
        // 获取角色所有权限
        $permissions = $user->getAllPermissions()->pluck('name')->toArray();
        // 对比用户权限
        if (in_array($permission, $permissions)) {
            return true;
        }

        return false;
    }
}