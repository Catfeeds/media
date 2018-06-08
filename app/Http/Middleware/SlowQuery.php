<?php

namespace App\Http\Middleware;

use Closure;

class SlowQuery
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //请求接口之前的时间
        $t1 = microtime(true);
        $response =  $next($request);
        //计算接口响应时间
        $time = (microtime(true) - $t1) * 1000;
        //如果接口响应时间大于1秒,则将接口记录到表中
        //获取接口名称
        $time = 1001;
        $url = $request->getRequestUri();
        if ($time >= 1000) {
            redirect('www.baidu.com');
        }
        return $response;
    }
}
