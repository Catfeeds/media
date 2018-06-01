<?php
/**
 * 公共方法
 * User: 罗振
 * Date: 2018/3/12
 * Time: 下午3:43
 */
namespace App\Handler;

use App\Models\OfficeBuildingHouse;
use Illuminate\Support\Facades\Auth;
use Qiniu\Storage\UploadManager;

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

    /**
     * 说明: curl请求获取数据
     *
     * @param $url
     * @return mixed
     * @author 罗振
     */
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

    /**
     * 说明: 获取七牛token
     *
     * @param null $accessKey
     * @param null $secretKey
     * @param null $bucket
     * @return string
     * @author 罗振
     */
    public static function getToken($accessKey = null, $secretKey = null, $bucket = null)
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
        $auth = new \Qiniu\Auth($accessKey, $secretKey);

        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        return $token;
    }

    /**
     * 说明: 七牛上传图片
     *
     * @param $filePath
     * @param $key
     * @return array
     * @throws \Exception
     * @author 罗振
     */
    public static function QiniuUpload($filePath, $key)
    {
        //获得token
        $token = self::getToken();

        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        $res = ['status' => true, 'url' => config('setting.qiniu_url') . $key];

        if (!$err == null) return ['status' => false, 'msg' => $err];

        return $res;
    }

    /**
     * 说明: 封装一个时间限制
     *
     * @return array
     * @author 李振
     */
    public static function getTime($day)
    {
        // 今天往前推n天的日期
       return [
            mktime(0, 0, 0, date('m')-6, date('d'), date('Y')),
            date("Y-m-d H:i:s", strtotime("-6 month"))
        ];
    }
}