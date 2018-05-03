<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use Laravel\Passport\Token;


class TokenInvalid extends Middleware
{

    /**
     * 说明:设置用户访问系统有效时间(30分钟)
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     * @author 刘坤涛
     */
    public function handle($request, Closure $next)
    {
        //获取用户登录信息
        $user = Auth::guard('api')->user();
        if (!empty($user)) {
            //判断redis中是否存在该用户对应的键
            if (\Redis::exists($user->id.'time')) {
                //如果redis中的时间+30分钟>当前时间
                if ((\Redis::get($user->id.'time') + 60*30) > time()) {
                    //访问其他页面时刷新有效时间
                    \Redis::set($user->id.'time',time());
                } else {
                    //如果30分钟有效期失效 删除redis中的键和token
                    //判断没有token
                    \Redis::del($user->id.'time');
                    $accessToken = $user->access_token;
                    if (!empty($accessToken)) {
                        $token = Token::find($accessToken);
                        if (!empty($token)) {
                            $token->delete();
                        }
                    }
                }
            }
        }
        return $next($request);
    }
}
