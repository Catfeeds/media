<?php
namespace App\Http\Controllers\API;

use App\Handler\Common;
use App\Http\Requests\API\HousesRequest;
use App\Models\DwellingHouse;
use App\Models\HouseImgRecord;
use App\Models\OfficeBuildingHouse;
use App\Models\ShopsHouse;
use App\Services\HousesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class HousesController extends APIBaseController
{

    /**
     * 说明:获取业主信息
     *
     * @param HousesRequest $request
     * @param HousesService $housesService
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function getOwnerInfo
    (
        HousesRequest $request,
        HousesService $housesService
    )
    {
        // 拿到房子
        $house = $housesService->getHouse ($request);
        // 判断当前登录用户是否有权限查看该房源业主信息和查看记录
        if (empty($house) || empty($house->see_power_cn)) return $this->sendError('房源异常');
        // 获取业主信息
        $ownerInfo = $house->owner_info;
        // 获取查看记录
        $viewRecord = $housesService->getViewRecord($house, $request->per_page);
        return $this->sendResponse( ['owner_info' => $ownerInfo, 'view_record' => $viewRecord],'业主信息,查看记录获取成功');
    }

    /**
     * 说明: 生成二维码
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function makeQrCode(
        Request $request
    )
    {
        // 登录用户
        $user = Auth::guard('api')->user();
        // 加密
        $parameter = $request->houseType.'/'.$request->houseId.'/'.time().'/'.$user->id;
        $encryption = Crypt::encryptString($parameter);
        $url = config('setting.agency_host') . '/mobileEditImg?miyao=' . $encryption;
        $result = QrCode::size(200)->generate($url);
        return $this->sendResponse($result,'二维码生成成功');
    }

    /**
     * 说明: 修改图片视图
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function houseImgUpdateView(
        Request $request
    )
    {
        // 解密
        $decrypt = Crypt::decryptString($request->token);
        // 处理数据
        $temp = explode('/', $decrypt);
        //
        if ($temp[0] == 1) {
            $house = DwellingHouse::find($temp[1]);
        } elseif ($temp[0] == 2) {
            $house = OfficeBuildingHouse::find($temp[1]);
        } elseif ($temp[0] == 3) {
            $house = ShopsHouse::find($temp[1]);
        } else {
            return $this->sendError('房源类型异常');
        }

        if (empty($house)) {
            return $this->sendError('房源异常');
        }

        //检测超时
//        if ($temp[2] + 120 < time()) {
//         return $this->sendError('二维码超时,请重新扫码');
//        }


        if ($house->guardian != (int)$temp[3] && strtotime($house->created_at->format('Y-m-d H:i:s')) + 12*60*60 > time()) {
            $house->operation = false;
        } else {
            $house->operation = true;
        }

        // 七牛域名
        $house->qiniu_url = config('setting.qiniu_url');
        $house->type = $temp[0];
        $house->user_id = $temp[3];
        return $this->sendResponse($house->makeHidden('see_power_cn'), '获取房源图片编辑信息成功');
    }

    /**
     * 说明: 扫码修改图片
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function houseImgUpdate(Request $request)
    {
        // 验证房源图片数量
        if (count($request->indoor_img) < 4) {
            return $this->sendError('图片数量最少四张');
        }

        // 更新house的图片
        if ($request->houseType == 1) {
            $model = 'App\Models\DwellingHouse';
        } elseif ($request->houseType == 2) {
            $model = 'App\Models\OfficeBuildingHouse';
        } elseif ($request->houseType == 3) {
            $model = 'App\Models\ShopsHouse';
        }
        // 查询房源数据
        $temp = $model::find($request->id);

        if (empty($temp)) return $this->sendError('房源异常');

        // 新房源十二小时跟进人可以操作
        if ($temp->guardian == (int)$request->user_id && strtotime($temp->created_at->format('Y-m-d H:i:s')) + 12*60*60 > time()) {
            $temp->indoor_img = $request->indoor_img;
            $temp->house_type_img = $request->house_type_img;
            if (empty($result = $temp->save())) return $this->sendError('修改失败');

        } elseif ($temp->guardian != (int)$request->user_id && strtotime($temp->created_at->format('Y-m-d H:i:s')) + 12*60*60 > time()) {
            return $this->sendError('该房源还处于保护期');
        } elseif (strtotime($temp->created_at->format('Y-m-d H:i:s')) + 12*60*60 < time()) {
            // 写入修改记录
            $result = HouseImgRecord::create([
                'user_id' => $request->user_id,
                'model' => $model,
                'house_id' => $temp->id,
                'indoor_img' => $request->indoor_img
            ]);
            if (empty($result)) return $this->sendError('记录表写入失败');
        }

        return $this->sendResponse(true, '操作成功');
    }

    /**
     * 说明: 房号验证
     *
     * @param HousesService $housesService
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author 李振
     */
    public function roomNumberValidate(HousesService $housesService,Request $request)
    {
        $request->model = '\App\Models\OfficeBuildingHouse';
        $res = $housesService->houseNumValidate($request);
        return $this->sendResponse($res['status'],$res['message']);
    }

    /**
     * 说明: 房源图片审核列表
     *
     * @param HousesService $housesService
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function houseImgAuditing(
        HousesService $housesService
    )
    {
        //判断用户权限
        if (empty(Common::user()->can('house_img_auditing'))) {
            return $this->sendError('没有房源修改图片审核列表权限','403');
        }

        $res = $housesService->houseImgAuditing();
        return $this->sendResponse($res,'房源图片审核列表获取成功');
    }

    /**
     * 说明: 房源图片审核详情
     *
     * @param Request $request
     * @param HousesService $housesService
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function houseImgAuditingDetails(
        Request $request,
        HousesService $housesService
    )
    {
        $res = $housesService->houseImgAuditingDetails($request);
        return $this->sendResponse($res,'房源图片审核详情获取成功');
    }

    /**
     * 说明: 审核操作
     *
     * @param Request $request
     * @param HousesService $housesService
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function auditingOperation(
        Request $request,
        HousesService $housesService
    )
    {
        $res = $housesService->auditingOperation($request);
        return $this->sendResponse($res,'审核操作成功');
    }
}