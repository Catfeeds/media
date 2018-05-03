<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Closure;

class TokenInvalid extends Middleware
{
    public function handle($request, Closure $next)
    {


        \Log::info('luo');
//        dd(url()->previous(), url()->current());


        // 获取上一个页面url
        $previousUrl = url()->previous();
        // 获取上次访问时间


        // 获取当前页面url
        $currentUrl = url()->current();
        // 当前页面访问时间


        if ($previousUrl !== $currentUrl) {
            $accessTime = time();
        }






        // 比较url




//        dd(Auth::guard('api')->user());





        return $next($request);
    }
}
