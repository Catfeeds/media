<?php

namespace App\Http\Controllers\API;

use App\Services\LoginService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Token;

class LoginController extends APIBaseController
{
    /**
     * 说明: 登录
     *
     * @param Request $request
     * @param LoginService $loginService
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function store(
        Request $request,
        LoginService $loginService
    )
    {
        $user = User::where([
            'tel' => $request->tel,
            'password' => bcrypt($request->pasword)
        ])->first();
        if (empty($user)) {
            return $this->sendError('用户名或密码错误');
        }

        $passport = $loginService->applyPasswordToken($request->tel, $request->password);

        // 最后登录时间
        $user->last_login_time = date('Y.m.d H:i:s', time());
        if (!$user->save()) {
            return $this->sendError('最后登录时间更新失败');
        }

        return $this->sendResponse($passport['message'], '获取token成功！');
    }

    /**
     * 说明: 退出登录
     *
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function logout()
    {
        $user = Auth::guard('api')->user();
        if (empty($user)) {
            return $this->sendError('暂未登录', 403);
        }

        // 获取当前登陆用户的access_token的id
        $accessToken = $user->access_token;

        // 找到这条access_token并且将其删除
        $token = Token::find($accessToken);
        if (empty($token)) {
            return $this->sendError('暂无有效令牌', 403);
        }

        if (!empty($token->delete())) {
            return $this->sendResponse([], '退出成功！');
        } else {
            return $this->sendError('退出失败', 500);
        }
    }
}
