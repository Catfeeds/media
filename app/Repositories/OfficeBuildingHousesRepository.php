<?php
namespace App\Repositories;

use App\Handler\Common;
use App\Models\Building;
use App\Models\OfficeBuildingHouse;
use App\Models\Storefront;
use App\Services\HousesService;
use App\User;
use Carbon\Carbon;

class OfficeBuildingHousesRepository extends BaseRepository
{

    private $model;

    public function __construct(OfficeBuildingHouse $model)
    {
        $this->model = $model;
    }

    /**
     * 说明: 写字楼房源列表
     *
     * @param $per_page
     * @param $request
     * @return mixed
     * @author 罗振
     */
    public function officeBuildingHousesList(
        $per_page,
        $request
    )
    {
        $result = $this->model->where('house_busine_state', $request->status);
        // 用户信息
        $user = Common::user();

        if (!empty($request->validList)) {
            if ($request->status == 7) {
                if ($user->level == 2) {
                    $storefront = Storefront::where(['area_manager_id' => $user->id])->first();
                    $users = User::where(['ascription_store' => $storefront->id])->pluck('id')->toArray();
                    $users[] = $user->id;
                    $result = $result->whereIn('guardian', $users);
                } elseif ($user->level == 3) {
                    $storefront = Storefront::where('user_id', $user->id)->first();
                    $users = User::where(['ascription_store' => $storefront->id])->pluck('id')->toArray();
                    $result = $result->whereIn('guardian', $users);
                } elseif ($user->level == 4) {
                    $result = $result->where('guardian', $user->id);
                }
            }

            if ($request->status == 1 || $request->status == 2) {
                // 有效房源
                if (!empty($request->order)) {
                    $result = $result->orderBy('start_track_time', $request->order);
                } else {
                    $result = $result->orderBy('start_track_time', 'desc');
                }
            } else {
                // 房源状态
                if (!empty($request->order)) {
                    $result = $result->orderBy('updated_at', $request->order);
                } else {
                    $result = $result->orderBy('updated_at', 'desc');
                }
            }
        } elseif (!empty($request->newHoustList)) {
            // 最新房源
            $result = $result->whereBetween('created_at', [date('Y-m-d H:i:s', strtotime('yesterday')), date('Y-m-d H:i:s', time())]);

            if (!empty($request->order)) {
                $result = $result->orderBy('created_at', $request->order);
            }
        } elseif (!empty($request->myHoustList)) {
            // 我的房源
            $result = $result->where('guardian', $user->id);
            if ($request->status != 7) {
                $orderBy = 'start_track_time';  // 排序
            } else {
                $orderBy = 'updated_at';    // 排序
            }

            if (!empty($request->order)) {
                $result = $result->orderBy($orderBy, $request->order);
            } else {
                $result = $result->orderBy($orderBy, 'desc');
            }
        }

        if (!empty($request->build)) {
            // 楼盘包含的楼座
            $blockId = array_column(Building::find($request->build)->buildingBlocks->toArray(), 'id');
            $result = $result->whereIn('building_block_id', $blockId);
        }

        // 最小面积
        if (!empty($request->min_acreage)) {
            $result = $result->where('constru_acreage', ">=", (int)$request->min_acreage);
        }
        // 最大面积
        if (!empty($request->max_acreage)) {
            $result = $result->where('constru_acreage', "<=", (int)$request->max_acreage);
        }

        // 最小单价
        if (!empty($request->min_unit_price)) {
            $result = $result->where('unit_price', '>=', $request->min_unit_price);
        }

        // 最大单价
        if (!empty($request->max_unit_price)) {
            $result = $result->where('unit_price', '<=', $request->max_unit_price);
        }

        // 房源编号
        if (!empty($request->house_identifier)) {
            $result = $result->where('house_identifier', $request->house_identifier);
        }

        return $result->paginate($per_page??10);
    }


    /**
     * 说明: 写字楼房源添加
     *
     * @param $request
     * @param HousesService $housesService
     * @return array
     * @author 罗振
     */
    public function addOfficeBuildingHouses(
        $request,
        HousesService $housesService
    )
    {
        \DB::beginTransaction();
        try {
            // 暂缓状态
            if ($request->house_busine_state == 3) {
                if (strtotime(date('Y-m-d', $request->rent_time)) - strtotime(Carbon::today()) < 60*24*60*60) {
                    return ['status' => false, 'message' => '暂缓状态到期时间必须大于今天60天'];
                }
            }

            // 获取房源名称
            $title = $housesService->getTitle($request);

            $house =  $this->model->create([
                'building_block_id' => $request->building_block_id,
                'house_number' => $request->house_number,
                'title' => $title,
                'owner_info' => $request->owner_info,
                'room' => $request->room,
                'hall' => $request->hall,
                'constru_acreage' => $request->constru_acreage,
                'split' => $request->split,
                'min_acreage' => $request->min_acreage,
                'floor' => $request->floor,
                'station_number' => $request->station_number,
                'office_building_type' => $request->office_building_type,
                'register_company' => $request->register_company,
                'open_bill' => $request->open_bill,
                'renovation' => $request->renovation,
                'orientation' => $request->orientation,
                'support_facilities' => $request->support_facilities??array(),
                'house_description' => $request->house_description,
                'unit_price' => $request->unit_price,    // 单价
                'total_price' => $request->unit_price * $request->constru_acreage,  // 总价
                'payment_type' => $request->payment_type,
                'check_in_time' => $request->check_in_time,
                'shortest_lease' => $request->shortest_lease,
                'rent_free' => $request->rent_free,
                'increasing_situation' => $request->increasing_situation,
                'increasing_situation_remark' => $request->increasing_situation_remark,
                'cost_detail' => $request->cost_detail??array(),
                'house_busine_state' => $request->house_busine_state,
                'pay_commission' => $request->pay_commission,
                'pay_commission_unit' => $request->pay_commission_unit,
                'prospecting' => $request->prospecting,
                'source' => $request->source,
                'house_key' => $request->house_key,
                'see_house_time' => $request->see_house_time,
                'see_house_time_remark' => $request->see_house_time_remark,
                'certificate_type' => $request->certificate_type,
                'house_proxy_type' => $request->house_proxy_type,
                'guardian' => Common::user()->id,
                'house_type_img' => $request->house_type_img,
                'indoor_img' => $request->indoor_img,
                'shelf' => 2,   // 默认不上架
                'start_track_time' => time(),
                'end_track_time' => time() + config('setting.house_to_public')*24*60*60,
                'rent_time' => strtotime($request->rent_time),  // 可租时间
                'remarks' => $request->remarks, // 信息不明确备注
            ]);
            if (empty($house)) {
                throw new \Exception('写字楼房源添加失败');
            }

            $house->house_identifier = $housesService->setHouseIdentifier('X', $house->id);
            if (empty($house->save())) {
                throw new \Exception('写字楼房源编号添加失败');
            }

            \DB::commit();
            return ['status' => true, 'message' => '写字楼房源添加成功'];
        } catch (\Exception $e) {
            \Log::error('写字楼房源添加失败：' . $e->getFile() . $e->getLine() . $e->getMessage());
            return ['status' => false, 'message' => '写字楼房源添加失败'];
        }
    }

    /**
     * 说明: 修改写字楼
     *
     * @param $officeBuildingHouse
     * @param $request
     * @return bool
     * @author 罗振
     */
    public function updateOfficeBuildingHouses(
        $officeBuildingHouse,
        $request
    )
    {
        // 获取房源名称
        $housesService = new HousesService();
        $title = $housesService->getTitle($request);

        $officeBuildingHouse->building_block_id = $request->building_block_id;
        $officeBuildingHouse->house_number = $request->house_number;
        $officeBuildingHouse->title = $title;
        $officeBuildingHouse->owner_info = $request->owner_info;
        $officeBuildingHouse->room = $request->room;
        $officeBuildingHouse->hall = $request->hall;
        $officeBuildingHouse->constru_acreage = $request->constru_acreage;
        $officeBuildingHouse->split = $request->split;
        $officeBuildingHouse->min_acreage = $request->min_acreage;
        $officeBuildingHouse->floor = $request->floor;
        $officeBuildingHouse->station_number = $request->station_number;
        $officeBuildingHouse->office_building_type = $request->office_building_type;
        $officeBuildingHouse->register_company = $request->register_company;
        $officeBuildingHouse->open_bill = $request->open_bill;
        $officeBuildingHouse->renovation = $request->renovation;
        $officeBuildingHouse->orientation = $request->orientation;
        $officeBuildingHouse->support_facilities = $request->support_facilities??array();
        $officeBuildingHouse->house_description = $request->house_description;
        $officeBuildingHouse->unit_price = $request->unit_price; // 单价
        $officeBuildingHouse->total_price = $request->unit_price * $request->constru_acreage;   // 总价
        $officeBuildingHouse->payment_type = $request->payment_type;
        $officeBuildingHouse->check_in_time = $request->check_in_time;
        $officeBuildingHouse->shortest_lease = $request->shortest_lease;
        $officeBuildingHouse->rent_free = $request->rent_free;
        $officeBuildingHouse->increasing_situation = $request->increasing_situation;
        $officeBuildingHouse->increasing_situation_remark = $request->increasing_situation_remark;
        $officeBuildingHouse->cost_detail = $request->cost_detail??array();
        $officeBuildingHouse->house_busine_state = $request->house_busine_state;
        $officeBuildingHouse->pay_commission = $request->pay_commission;
        $officeBuildingHouse->pay_commission_unit = $request->pay_commission_unit;
        $officeBuildingHouse->prospecting = $request->prospecting;
        $officeBuildingHouse->source = $request->source;
        $officeBuildingHouse->house_key = $request->house_key;
        $officeBuildingHouse->see_house_time = $request->see_house_time;
        $officeBuildingHouse->see_house_time_remark = $request->see_house_time_remark;
        $officeBuildingHouse->certificate_type = $request->certificate_type;
        $officeBuildingHouse->house_proxy_type = $request->house_proxy_type;
        $officeBuildingHouse->house_type_img = $request->house_type_img;
        $officeBuildingHouse->indoor_img = $request->indoor_img;
        $officeBuildingHouse->shelf = $request->shelf;
        $officeBuildingHouse->rent_time = $request->rent_time;  // 可租时间
        $officeBuildingHouse->remarks = $request->remarks;  // 信息不明确备注

        if (!$officeBuildingHouse->save()) {
            return false;
        }

        return true;
    }

    /**
     * 说明: 修改写字楼房源业务状态
     *
     * @param $request
     * @return mixed
     * @author 刘坤涛
     */
    public function updateState($request)
    {
        return $this->model->where('id', $request->id)->update([
            'house_busine_state' => $request->house_busine_state
        ]);
    }

    /**
     * 说明: 上线房源
     *
     * @param $request
     * @return mixed
     * @author 罗振
     */
    public function updateShelf($request)
    {
        return $this->model->where('id', $request->id)->update([
            'shelf' => 1
        ]);
    }
}