<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Qiniu\Auth;

class QiNiuController extends Controller
{
    /**
     * 说明：返回七牛token
     *
     * @param null $accessKey
     * @param null $secretKey
     * @param null $bucket
     * @return string
     * @author jacklin\
     */
    public function index($accessKey = null, $secretKey = null, $bucket = null)
    {
        if (empty($accessKey)) {
            $accessKey = config('setting.qiniu_access_key');
        }
        if (empty($secretKey)) {
            $secretKey = config('setting.qiniu_secret_key');
        }
        if (empty($bucket)) {
            $bucket = config('setting.qiniu_bucket');
        }
        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);

        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        return $token;
    }
}
