<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Crypt;

class SafeValidate
{
    /**
     * 说明: 安全验证
     *
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     * @author 罗振
     */
    public function handle(
        $request,
        Closure $next
    )
    {
        Crypt::decryptString($request->header('safeString'));

        try {
            if (empty($request->header('safeString')) || Crypt::decryptString($request->header('safeString')) != 'chulouwang'.date('Y-m-d',time())) {
                return response()->json(["message" => "认证失败"], 401);
            }
        } catch (\Exception $e) {
            \Log::info($e->getFile().$e->getLine().$e->getMessage());
            return response()->json(["message" => "认证失败"], 401);
        }

        return $next($request);
    }
}
