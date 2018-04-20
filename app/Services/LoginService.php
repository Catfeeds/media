<?php

namespace App\Services;

use GuzzleHttp\Client;

class LoginService
{
    /**
     * 说明: 申请密码token
     *
     * @param $username
     * @param $password
     * @param null $client_id
     * @param null $client_secret
     * @param string $scope
     * @return array
     * @author 罗振
     */
    public function applyPasswordToken($username, $password, $client_id = null, $client_secret = null, $scope = '')
    {
        $data = [
            'username' => $username,
            'password' => $password,
            'scope' => $scope??config('passport.default.scope'),
            'client_id' => $client_id??config('passport.default.client_id'),
            'client_secret' => $client_secret??config('passport.default.client_secret')
        ];
        $http = new Client();
        $result = null;
        $data['grant_type'] = 'password';
        try {
            $result = $http->post(url('/oauth/token'), [
                'form_params' => $data,
            ]);
        } catch (\Exception $e) {
            \Log::error('申请密码令牌失败：字段信息为：' . json_encode($data) . '错误：' . $e->getMessage());
            $error = explode("\n", $e->getMessage())[1];
            if ($error[strlen($error) - 1] != '}') {
                $error = $error . '"}';
            }

            if (empty(json_decode($error))) return ['success' => false, 'message' => '服务器异常，请联系管理员'];

            switch (json_decode($error)->message) {
                case 'The user credentials were incorrect.':
                    $resultData = '用户名或密码错误！';
                    break;
                case 'Client authentication failed':
                case 'The requested scope is invalid, unknown, or malformed':
                    $resultData = '客户端出错，请重新下载！';
                    break;
                default:
                    $resultData = '未知错误，请联系管理员！';
                    break;
            }
            return ['success' => false, 'message' => $resultData];
        }
        return ['success' => true, 'message' => '获取成功', 'data' => [
            'token' => json_decode((string)$result->getBody(), true)['access_token']
        ]];
    }

}