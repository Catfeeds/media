<?php
namespace App\Http\Controllers\API;

use App\Handler\Common;
use App\Http\Requests\API\HousesRequest;
use App\Models\DwellingHouse;
use App\Models\OfficeBuildingHouse;
use App\Models\ShopsHouse;
use App\Services\HousesService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Qiniu\Auth;
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
        $house = $housesService->getHouse($request);
        // 判断当前登录用户是否有权限查看该房源业主信息和查看记录
        if (empty($house) || empty($house->see_power_cn)) return $this->sendError('房源异常');
        // 获取业主信息
        $ownerInfo = $house->owner_info;
        // 获取查看记录
        $viewRecord = $housesService->getViewRecord($house, $request->per_page);
        return $this->sendResponse( ['owner_info' => $ownerInfo, 'view_record' => $viewRecord],'业主信息,查看记录获取成功');
    }

    // TODO
    public function makeQrCode(
        Request $request
    )
    {
        // 加密
        $parameter = $request->houseType.'/'.$request->houseId.'/'.time();
        $encryption = Crypt::encryptString($parameter);
        $url = 'http://192.168.0.188:9528/#/mobileEditImg?miyao='.$encryption;
        $result = QrCode::size(200)->generate($url);
        return $this->sendResponse($result,'二维码生成成功');
    }

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
        // 检测超时
        // if ($temp[2] + 120 < time()) {
        //     return $this->sendError('二维码超时,请重新扫码');
        // }
        
        // 七牛域名
        $house->qiniu_url = config('setting.qiniu_url');
        $house->type = $temp[0];
        return $this->sendResponse($house->makeHidden('see_power_cn'), '获取房源图片编辑信息成功');
    }

    public function houseImgUpdate(
        Request $request
    )
    {
        // 更新house的图片
        if ($request->houseType == 1) {
            $temp = DwellingHouse::find($request->id);
        } elseif ($request->houseType == 2) {
            $temp = OfficeBuildingHouse::find($request->id);
        } elseif ($request->houseType == 3) {
            $temp = ShopsHouse::find($request->id);
        }
        
        if (empty($temp)) {
            return $this->sendError('房源异常');
        }

        $temp->indoor_img = $request->indoor_img;
        $temp->house_type_img = $request->house_type_img;
        if (empty($result = $temp->save())) {
            return $this->sendError('修改失败');
        }

        return $this->sendResponse($result, '修改成功');
    }
}