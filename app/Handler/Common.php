<?php
/**
 * 公共方法
 * User: 罗振
 * Date: 2018/3/12
 * Time: 下午3:43
 */
namespace App\Handler;

use Illuminate\Support\Facades\Auth;

/**
 * Class Common
 * 常用工具类
 * @package App\Tools
 */
class Common
{
    /**
     * 说明: 用户信息
     *
     * @return mixed
     * @author 罗振
     */
    public static function user()
    {
        // 判断用户权限
        return Auth::guard('api')->user();
    }
}