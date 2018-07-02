<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Hash;

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
        try {
            if (!Hash::check('chulouwang'.date('Y-m-d',time()), $request->header('safeString'))) {
                return response()->json(["message" => "认证失败"], 401);
            }
        } catch (\Exception $e) {
            \Log::info($e->getFile().$e->getLine().$e->getMessage());
            return response()->json(["message" => "认证失败"], 401);
        }

        return $next($request);
    }
}
