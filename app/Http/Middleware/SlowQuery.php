<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client;


class SlowQuery
{
    /**
     * 说明:
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author 刘坤涛
     */
    public function handle($request, Closure $next)
    {
        //计算接口响应时间
        $t1 = microtime(true);
        $response =  $next($request);
        $time = (microtime(true) - $t1) * 1000;
        //如果时间大于1s,请求query接口,将该接口插入数据表中
        if ($time >= 1000) {
            $data = [
                'app_name' => env('APP_NAME'),
                'url' => $request->getRequestUri(),
                'time' => $time
            ];
            $http = new Client();
            try {
                $http->request('POST', config('setting.http_url'), [
                    'form_params' => $data
                ]);
            } catch (\Exception $e) {
                \Log::info('记录失败'.$e->getMessage());
            }
        }
        return $response;
    }
}
