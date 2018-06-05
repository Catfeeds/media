<?php

namespace App\Services;



use App\Models\Custom;
use App\Models\HouseImgRecord;
use App\Models\OfficeBuildingHouse;
use App\Models\OwnerViewRecord;
use App\Models\Storefront;
use App\Models\Track;
use App\User;

class HomePagesService
{
    private $year;
    private $month;
    private $day;

    public function __construct()
    {
        $this->year =  date('Y');//当前年份
        $this->month = date('m');//当前月份
        $this->day = date('d');  //当前日期
    }

    /**
     * 说明: 转换日期格式
     *
     * @param $time
     * @return mixed
     * @author 刘坤涛
     */
    public function getDate($start, $end)
    {

        $date['start'] = date('Y-m-d H:i:s', $start);
        $date['end'] = date('Y-m-d H:i:s', $end);
        return $date;
    }

    /**
     * 说明: 获取当天时间戳
     *
     * @return mixed
     * @author 刘坤涛
     */
    public function getDayTime()
    {
        $start = mktime(0, 0, 0, $this->month, $this->day, $this->year);
        $end = mktime(23, 59, 59, $this->month, $this->day, $this->year);
        return $this->getDate($start, $end);
    }

    /**
     * 说明: 获取本周时间戳
     *
     * @return mixed
     * @author 刘坤涛
     */
    public function getWeekTime()
    {
        $start = strtotime('Sunday -6 day',strtotime($this->year.'-'.$this->month.'-'.$this->day));
        $end = strtotime('Monday 7 day',strtotime($this->year.'-'.$this->month.'-'.$this->day)) - 1;
        return $this->getDate($start, $end);
    }

    /**
     * 说明: 获取本月时间戳
     *
     * @return mixed
     * @author 刘坤涛
     */
    public function getMonthTime()
    {
        $start = mktime(0,0,0, $this->month,1, $this->year);
        $end = mktime(23,59,59, $this->month, date('t'), $this->year);
        return $this->getDate($start, $end);
    }

    /**
     * 说明: 获取本年度半年时间戳
     *
     * @return mixed
     * @author 刘坤涛
     */
    public function getYearTime()
    {
        if ($this->month <= 6) {
            $start = mktime(0,0,0,1,1,$this->year);
            $end = mktime(23,59,59,6,30,$this->year);
        } else {
            //如果是下半年
            //计算7月1号 和12月31号23:59:59秒的时间戳
            $start = mktime(0,0,0,7,1,$this->year);
            $end = mktime(23,59,59,12,31,$this->year);
        }
        return $this->getDate($start, $end);
    }


    /**
     * 说明: 获取逾期时间(天,小时)
     *
     * @param $time
     * @author 刘坤涛
     */
    public function time($second)
    {
        $timestamps = $second / (24*3600);
        if ($timestamps > 1) {
            $day = (int)substr($timestamps,0,1);
            $hour = ($timestamps - $day) * 24;
            $time = $day . '天' . (int)$hour . '小时';
        } else {
            $time = (int)($timestamps * 24) . '小时';
        }
        return $time;
    }

    /**
     * 说明: 后台首页数据
     *
     * @param $date
     * @param $user
     * @return array
     * @author 刘坤涛
     */
    public function getData($time, $id)
    {
        switch ($time) {
            case 1:
                $date = $this->getDayTime();
                break;
            case 2:
                $date = $this->getWeekTime();
                break;
            case 3:
                $date = $this->getMonthTime();
                break;
            case 4:
                $date = $this->getYearTime();
                break;
                default;
                break;
        }
        $data = [];
        //获取该用户的新增房源数量
        $data['house_num'] = OfficeBuildingHouse::where('guardian', $id)->whereBetween('created_at', $date)->count();
        //获取该用户新增客源
        $data['customer_num'] = Custom::where('guardian', $id)->whereBetween('created_at', $date)->count();
        //获取房源更进次数
        $data['house_tracks_num'] = Track::where(['user_id'=> $id, 'house_model' => 'App\Models\OfficeBuildingHouse'])->whereBetween('created_at', $date)->count();
        //客源跟进次数
        $data['customer_tracks_num'] = Track::where(['house_model' => null, 'user_id' => $id])->whereBetween('created_at', $date)->count();
        //房源带看
        $data['lead_house'] = Track::where(['house_model' => 'App\Models\OfficeBuildingHouse', 'tracks_mode' => 7, 'user_id' => $id])->whereBetween('created_at', $date)->count();
        //客源带看
        $data['lead_customer'] = Track::where(['house_model' => null, 'tracks_mode' => 7, 'user_id' => $id])->whereBetween('created_at', $date)->count();
        //上传图片
        $data['upload_img'] = HouseImgRecord::where(['user_id' => $id, 'status' => 2])->whereBetween('created_at', $date)->count();
        //查看信息次数
        $data['view_record_num'] = OwnerViewRecord::where('user_id', $id)->whereBetween('created_at', $date)->count();
        return $data;
    }

    /**
     * 说明: 获取待跟进房源数据
     *
     * @param $id
     * @return array
     * @author 刘坤涛
     */
    public function waitTrackHouse($id)
    {
        //查询该用户的待跟进房源并且逾期时间在2天以内的房源ID
        $houseId = OwnerViewRecord::where(['user_id' => $id, 'status' => 1, 'house_model' => 'App\Models\OfficeBuildingHouse'])->pluck('house_id')->toArray();
        //查询出对应的房子
        $house = OfficeBuildingHouse::whereIn('id',$houseId)->with('buildingBlock','buildingBlock.building')->where('end_track_time','<', (time() + 48*3600))->get();
        $data = [];
        foreach($house as $k => $v) {
            $data[$k]['house_id'] = $v->id;
            $data[$k]['house_name'] = $v->buildingBlock->building->name. ' '. $v->buildingBlock->name .$v->buildingBlock->name_unit.$v->house_number.'室';
            $data[$k]['over_time'] = $this->time($v->end_track_time - time());
        }
        return $data;
    }

    /**
     * 说明: 获取新增数据
     *
     * @param $userId
     * @param $model
     * @param null $time
     * @return mixed
     * @author 刘坤涛
     */
    public function getAddedData($model, $userId = null, $time = null)
    {
        if (!$time && $userId) return  $model::whereIn('guardian', $userId)->count();
        if ($time && !$userId) return  $model::whereBetween('created_at', $time)->count();
        return $model::whereIn('guardian', $userId)->whereBetween('created_at', $time)->count();
    }

    /**
     * 说明: 写字楼统计数据
     *
     * @param $class
     * @param $id
     * @return array
     * @author 刘坤涛
     */
    public function officeStatistic($class, $id)
    {
        $storefrontId = User::where('id', $id)->first()->ascription_store;
        $model = 'App\Models\OfficeBuildingHouse';
        //今日时间
        $day = $this->getDayTime();
        //本周时间
        $week = $this->getWeekTime();
        //本月时间
        $month = $this->getMonthTime();
        $data= [];
        switch ($class) {
            case 1:
                //查询该业务员所属门店的房源新增数据
                //通过门店id查询出该门店所有人员
                $userId = User::where('ascription_store', $storefrontId)->pluck('id')->toArray();
                //今日新增
                $data['day_added'] = $this->getAddedData($model, $userId, $day);
                //本周新增
                $data['week_added'] = $this->getAddedData($model, $userId, $week);
                //本月新增
                $data['month_added'] = $this->getAddedData($model, $userId, $month);
                //全部写字楼
                $data['all_added'] = $this->getAddedData($model, $userId);
                break;
            case 2:
                //查询该业务员所属区域的房源新增数据
                //查询该门店的区域经理
                $managerId = Storefront::where('id', $storefrontId)->first()->area_manager_id;
                //查询该区域经理下的所有门店
                $storefrontsId = Storefront::where('area_manager_id', $managerId)->pluck('id')->toArray();
                //查询这些门店下的所有人员
                $userId = User::whereIn('ascription_store', $storefrontsId)->pluck('id')->toArray();
                //查询今日新增
                $data['day_added'] = $this->getAddedData($model, $userId, $day);
                $data['week_added'] = $this->getAddedData($model, $userId, $week);
                $data['month_added'] = $this->getAddedData($model, $userId, $month);
                $data['all_added'] = $this->getAddedData($model, $userId);
                break;
            case 3:
                //查询平台新增
                $data['day_added'] = $this->getAddedData($model, null, $day);
                $data['week_added'] = $this->getAddedData($model, null, $week);
                $data['month_added'] = $this->getAddedData($model, null, $month);
                $data['all_added'] = OfficeBuildingHouse::count();
                break;
                default;
                break;
        }
        return $data;
    }





    
}