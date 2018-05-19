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

    public static function getCurl($url)
    {
        $curlobj = curl_init();
        curl_setopt($curlobj, CURLOPT_URL, $url);
        curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, 1);
        // 验证
        curl_setopt($curlobj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlobj, CURLOPT_SSL_VERIFYHOST, 0);
        $res = curl_exec($curlobj);
        curl_close($curlobj);
        return $res;
    }
}