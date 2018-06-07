<?php

namespace App\Services;

use App\Models\BrowseRecord;
use App\Models\Building;
use App\Models\BuildingBlock;
use App\Models\Collection;
use App\Models\HouseImgRecord;
use App\Models\OfficeBuildingHouse;
use App\Models\OwnerViewRecord;
use App\User;

class HousesService
{
    /**
     * 说明: 房源编号
     *
     * @param $initials
     * @param $houseId
     * @return string
     * @author 罗振
     */
    public function setHouseIdentifier($initials, $houseId)
    {
        if (strlen($houseId) == 1) {
            return $initials.date('Ymd', time()).'00'.$houseId;
        } elseif (strlen($houseId) == 2) {
            return $initials.date('Ymd', time()).'0'.$houseId;
        } else {
            return $initials.date('Ymd', time()).$houseId;
        }
    }

    /**
     * 说明: 通过楼座获取城市
     *
     * @param $BuildingBlockId
     * @return array
     * @author 罗振
     */
    public function adoptBuildingBlockGetCity($BuildingBlockId)
    {
        $temp = BuildingBlock::find($BuildingBlockId);

        // 拼接商圈获取城市数据
        $arr[] = $temp->building->area->city->id;
        $arr[] = $temp->building->area->id;
        $arr[] = $temp->building->id;
        $arr[] = $BuildingBlockId;

        return $arr;
    }

    /**
     * 说明：通过表格楼栋分割成标准数据
     *
     * @param $str
     * @return array
     * @author jacklin
     */
    public function blockForm($str)
    {
        $data = array();
        // 如果有 '栋'
        $dong = mb_strstr($str, '栋', true);
        if (!empty($dong)) {
            $data['name'] = $dong;
            $data['name_unit'] = '栋';
            $str = explode('栋', $str)[1];
        }

        $zuo = mb_strstr($str, '座', true);
        if (!empty($zuo)) {
            $data['name'] = $zuo;
            $data['name_unit'] = '座';
            $str = explode('座', $str)[1];
        }

        $danyuan = mb_strstr($str, '单元', true);
        if (!empty($danyuan)) {
            $data['unit'] = $danyuan;
            $data['unit_unit'] = '单元';
        } else {
            $danyuan = mb_strstr($str, '门', true);
            if (!empty($danyuan)) {
                $data['unit'] = $danyuan;
                $data['unit_unit'] = '门';
            }
        }
        if (empty($data)) return array('name' => '独', 'name_unit' => '栋');
        return $data;
    }

    /**
     * 说明: 房号验证
     *
     * @param $request
     * @param null $officeBuildingHouse
     * @return array
     * @author 罗振
     */
    public function houseNumValidate(
        $request,
        $officeBuildingHouse = null
    )
    {
        // 修改验证
        if (!empty($officeBuildingHouse)) {
            if ($request->house_number == $officeBuildingHouse->house_number && $request->floor == $officeBuildingHouse->floor && $request->building_block_id == $officeBuildingHouse->building_block_id) {
                return [
                    'status' => true,
                    'message' => '验证成功'
                ];
            }
        }

        // 处理房号
        $temp = explode('-', $request->house_number);

        // 判断是否为整层
        if (count($temp) > 1) {
            // 包含英文验证
            $preg2= '/[A-Za-z]/';
            if(preg_match($preg2, $request->house_number)){
                return [
                    'status' => false,
                    'message' => '所填房号格式不正确'
                ];
            }

            foreach ($temp as $v) {
                // 判断楼层
                $temp2 = strpos($v, $request->floor);
                // 判断楼层是否正确(肯定是从第0位开始)
                if ($temp2 !== 0) {
                    return [
                        'status' => false,
                        'message' => '楼层与房号不是同一层',
                    ];
                }

                // 判断是否有这个房号
                $temp3 = $request->model::where([
                    'building_block_id' => $request->building_block_id,
                    'house_number' => $v,
                    'floor' => $request->floor
                ])->first();
                if ($temp3) {
                    return [
                        'status' => false,
                        'message' => '该房号已存在',
                    ];
                }
            }
        } else {
            // 包含英文验证
            $preg2= '/[A-Za-z]/';
            if(preg_match($preg2, $request->house_number)){
                // 判断是否包含F
                if ($request->house_number !== 'F') {
                    return [
                        'status' => false,
                        'message' => '所填房号格式不正确',
                    ];
                }
            } else {
                // 判断楼层
                $temp2 = strpos($request->house_number, $request->house_number);
                // 判断楼层是否正确(肯定是从第0位开始)
                if ($temp2 !== 0) {
                    return [
                        'status' => false,
                        'message' => '楼层与房号不是同一层',
                    ];
                }
            }

            // 判断楼座中否是有这个房号
            $temp3 = $request->model::where([
                'building_block_id' => $request->building_block_id,
                'house_number' => $request->house_number,
                'floor' => $request->floor
            ])->first();
            if ($temp3) {
                return [
                    'status' => false,
                    'message' => '该房号已存在',
                ];
            }
        }

        return [
            'status' => true,
            'message' => '验证成功'
        ];
    }


    /**
     * 说明:获取房源信息
     *
     * @param $request
     * @return mixed
     * @author 刘坤涛
     */
    public function getHouse($request)
    {
        switch ($request->house_model) {
            case '1':
                $model = "App\\Models\\DwellingHouse";
                break;
            case '2':
                $model = "App\\Models\\OfficeBuildingHouse";
                break;
            case '3':
                $model = "App\\Models\\ShopsHouse";
                break;
            default;
                break;
        }
        return  $model::find($request->house_id);
    }

    /**
     * 说明:获取查看记录
     *
     * @param $house
     * @param null $per_page
     * @return mixed
     * @author 刘坤涛
     */
    public function getViewRecord($house, $per_page = null)
    {
        $model = get_class($house);
        return  OwnerViewRecord::where(['house_id' => $house->id, 'house_model' => $model])->paginate($per_page);
    }

    /**
     * 说明: 拼接房源名称
     *
     * @param $request
     * @return string
     * @author 罗振
     */
    public function getTitle(
        $request
    )
    {
        $string = '';
        $temp = BuildingBlock::with(['building.area'])->find($request->building_block_id);

        $string .= $temp->Building->area->name;
        $string .= '['.$temp->Building->name.']';

        if (!empty($request->office_building_type)) {
            if ($request->office_building_type == 1) {
                $string .= '纯写字楼';
            } elseif ($request->office_building_type == 2) {
                $string .= '商住楼';
            } elseif ($request->office_building_type == 3) {
                $string .= '商业综合体楼';
            } elseif ($request->office_building_type == 4) {
                $string .= '酒店写字楼';
            } elseif ($request->office_building_type == 5) {
                $string .= '其他';
            }
        }

        if (!empty($request->renovation)) {
            if ($request->renovation == 1) {
                $string .= '-豪华装修';
            } elseif ($request->renovation == 2) {
                $string .= '-精装修';
            } elseif ($request->renovation == 3) {
                $string .= '-中装修';
            } elseif ($request->renovation == 4) {
                $string .= '-间装修';
            } elseif ($request->renovation == 5) {
                $string .= '-毛坯';
            }
        }

        if (!empty($request->register_company)) {
            if ($request->register_company == 1) {
                $string .= '-可注册';
            } elseif ($request->register_company == 2) {
                $string .= '-不可注册';
            }
        }

        if (!empty($request->constru_acreage)) {
            $string .= '-'.$request->constru_acreage.'㎡';
        }

        return $string;
    }

    /**
     * 说明: 删除房源
     *
     * @param OfficeBuildingHouse $officeBuildingHouse
     * @return bool
     * @author 罗振
     */
    public function delHouse(
        OfficeBuildingHouse $officeBuildingHouse
    )
    {
        \DB::connection('mysql')->beginTransaction();
        \DB::connection('clw')->beginTransaction();
        try {
            $delHouse = $officeBuildingHouse->delete();
            if (empty($delHouse)) throw new \Exception('删除写字楼房源失败');

            // 获取房源相关的浏览记录
            $browseRecordId = $officeBuildingHouse->BrowseRecord->pluck('id')->toArray();
            if (!empty($browseRecordId)) {
                $delBrowseRecord = BrowseRecord::destroy($browseRecordId);
                if (empty($delBrowseRecord)) throw new \Exception('房源相关的浏览记录删除失败');
            }

            // 获取房源相关的收藏
            $collectionId = $officeBuildingHouse->Collection->pluck('id')->toArray();
            if (!empty($collectionId)) {
                $delCollection = Collection::destroy($collectionId);
                if (empty($delCollection)) throw new \Exception('房源相关的收藏记录删除失败');
            }

            \DB::connection('mysql')->commit();
            \DB::connection('clw')->commit();
            return true;
        } catch (\Exception $e) {
            \DB::connection('mysql')->rollBack();
            \DB::connection('clw')->rollBack();
            \Log::error('写字楼房源删除失败'. $e->getMessage());
            return false;
        }
    }

    /**
     * 说明: 房源图片审核列表
     *
     * @return mixed
     * @author 罗振
     */
    public function houseImgAuditing($condition)
    {
        $temp = HouseImgRecord::where(['model' => 'App\Models\OfficeBuildingHouse']);
        // 申请人
        if (!empty($condition->applicant)) {
            $userId = User::where('real_name', $condition->applicant)->pluck('id')->toArray();
            $temp = $temp->whereIn('user_id', $userId);
        }

        // 排序
        if (!empty($condition->order)) {
            $temp = $temp->orderBy('created_at', $condition->order);
        }

        $houseId = $temp->pluck('house_id')->toArray();

        $res = OfficeBuildingHouse::whereIn('id', $houseId)->orderByRaw("FIELD(id, " . implode(", ", $houseId) . ")");

        // 楼盘
        if (!empty($condition->build)) {
            // 楼盘包含的楼座
            $blockId = array_column(Building::find($condition->build)->buildingBlocks->toArray(), 'id');
            $res = $res->whereIn('building_block_id', $blockId);
        }

        // 维护人
        if (!empty($condition->guardian_cn)) {
            $userId = User::where('real_name', $condition->guardian_cn)->pluck('id')->toArray();
            $res = $res->whereIn('guardian', $userId);
        }

        // 房源编号
        if (!empty($condition->house_number)) {
            $res = $res->where('house_identifier', $condition->house_number);
        }

        $officeBuildingHouse = $res->paginate(10);


        foreach ($officeBuildingHouse as $v) {
            $v->applicant = $v->houseImgRecord->user->real_name;
            $v->buildingName = $v->buildingBlock->building->name;
            $v->record_status_cn = $v->houseImgRecord->status_cn;
            $v->create_time = $v->houseImgRecord->created_at->format('Y-m-d H:i:s');
            $v->record_id = $v->houseImgRecord->id;
        }

        return $officeBuildingHouse;
    }

    /**
     * 说明: 房源图片审核详情
     *
     * @param $request
     * @return mixed
     * @author 罗振
     */
    public function houseImgAuditingDetails(
        $request
    )
    {
        $houseImgRecord = HouseImgRecord::where(['model' => 'App\Models\OfficeBuildingHouse', 'id' => $request->id])->with('officeBuildingHouse.buildingBlock.building.area.city')->first();

        $houseImgRecord->applicant = $houseImgRecord->user->real_name;
        $houseImgRecord->houseName = $houseImgRecord->officeBuildingHouse->house_number;
        $houseImgRecord->guardian_cn = $houseImgRecord->officeBuildingHouse->guardian_cn;
        $houseImgRecord->building = $houseImgRecord->officeBuildingHouse->buildingBlock->building->name;
        $houseImgRecord->house_identifier = $houseImgRecord->officeBuildingHouse->house_identifier;
        $houseImgRecord->old_indoor_img_cn = $houseImgRecord->officeBuildingHouse->indoor_img_cn;

        return $houseImgRecord;
    }

    /**
     * 说明: 审核操作
     *
     * @param $request
     * @return mixed
     * @author 罗振
     */
    public function auditingOperation(
        $request
    )
    {
        \DB::beginTransaction();
        try {
            $houseImgRecord = HouseImgRecord::find($request->id);
            $houseImgRecord->status = $request->status;
            $houseImgRecord->remarks = $request->remarks;
            if (!$houseImgRecord->save()) throw new \Exception('状态修改失败');

            if ($request->status == 3) {
                $house = OfficeBuildingHouse::find($houseImgRecord->house_id);
                $house->indoor_img = $houseImgRecord->indoor_img;
                if (!$house->save()) throw new \Exception('写字楼房源图片修改失败');
            }

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::error($exception->getMessage());
            return false;
        }
    }
}