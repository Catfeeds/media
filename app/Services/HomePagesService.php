<?php

namespace App\Services;

use App\Models\Custom;
use App\Models\GroupAssociation;
use App\Models\HouseImgRecord;
use App\Models\OfficeBuildingHouse;
use App\Models\OwnerViewRecord;
use App\Models\Storefront;
use App\Models\Track;
use App\User;
use Illuminate\Support\Facades\Auth;


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
     * 说明: 获取用户信息
     *
     * @return mixed
     * @author 罗振
     */
    public function user()
    {
        return Auth::guard('api')->user();
    }

    /**
     * 说明: 转换日期格式
     *
     * @param $start
     * @param $end
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
     * 说明: 获取上周第一天和最后一天时间戳
     *
     * @return mixed
     * @author 刘坤涛
     */
    public function getLastWeekTime()
    {
        $start = mktime(0,0,0,$this->month,$this->day - date('w')+1-7,$this->year);
        $end = mktime(23,59,59,$this->month,$this->day - date('w')+7-7,$this->year);
        return $this->getDate($start, $end);
    }

    /**
     * 说明: 获取本周每天时间戳
     *
     * @return array
     * @author 刘坤涛
     */
    public function getWeekDayTime()
    {
        $arr[0]= time()-((date('w')==0?7:date('w'))-1)*24*3600;
        $arr[1]= time()-((date('w')==0?7:date('w'))-2)*24*3600;
        $arr[2]= time()-((date('w')==0?7:date('w'))-3)*24*3600;
        $arr[3]= time()-((date('w')==0?7:date('w'))-4)*24*3600;
        $arr[4]= time()-((date('w')==0?7:date('w'))-5)*24*3600;
        $arr[5]= time()-((date('w')==0?7:date('w'))-6)*24*3600;
        $arr[6]= time()-((date('w')==0?7:date('w'))-7)*24*3600;
        $new = [];
        foreach ($arr as $k => $v) {
            $new[$k] = $this->getDate(mktime(0,0,0,date("m",$v),date("d",$v),date("Y",$v)), mktime(23,59,59,date("m",$v),date("d",$v),date("Y",$v)));
        }
        return $new;
    }

    public function getMonthDayTime()
    {
        $start_time = strtotime(date('Y-m-01'));  //获取本月第一天时间戳
        $array = [];
        for($i=0; $i< date('t'); $i++){
            $time = $start_time+$i*86400; //循环加一天的时间描述
            $array[] = $this->getDate(mktime(0,0,0,date("m",$time),date("d",$time),date("Y",$time)), mktime(23,59,59,date("m",$time),date("d",$time),date("Y",$time)));
        }
        return $array;
    }

    /**
     * 说明: 获取上月第一天和最后一天时间戳
     *
     * @return mixed
     * @author 刘坤涛
     */
    public function getLastMonthTime()
    {
        $start = mktime(0,0,0,$this->month-1,1, $this->year);
        $end = mktime(23,59,59,$this->month-1, date("t",$start),date("Y",$start));
        return $this->getDate($start, $end);
    }

    /**
     * 说明: 获取本月第一天和最后一天时间戳
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
     * 说明: 获取本年度半年内第一天和最后一天时间戳
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
     * @param $second
     * @return string
     * @author 刘坤涛
     */
    public function time($second)
    {
        $timestamps = $second / (24*3600); //多少天
        //剩余时间大于一天
        if ($timestamps > 1) {
            $day = (int)substr($timestamps,0,1);
            $hour = ($timestamps - $day) * 24;
            $time = $day . '天' . (int)$hour . '小时';
            //剩余时间小于一天
        } else {
            $time = (int)($timestamps * 24) . '小时';
        }
        return $time;
    }

    /**
     * 说明: 后台首页数据个人新增数据
     *
     * @param $time
     * @return array
     * @author 刘坤涛
     */
    public function getData($time, $id = null)
    {
        if (is_int((int)$time)) {
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
        }

        if (is_array($time)) {
            $date = $time;
        }

        if (!$id) {
            // 获取用户id
            $id = $this->user()->id;
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
        $data['upload_img'] = HouseImgRecord::where(['user_id' => $id, 'status' => 3])->whereBetween('created_at', $date)->count();
        //查看信息次数
        $data['view_record_num'] = OwnerViewRecord::where('user_id', $id)->whereBetween('created_at', $date)->count();
        return $data;
    }

    /**
     * 说明: 获取待跟进房源数据
     *
     * @return array
     * @author 刘坤涛
     */
    public function waitTrackHouse()
    {
        // 获取用户id
        $id = $this->user()->id;
        //查询该用户的待跟进房源id
        $houseId = OwnerViewRecord::where(['user_id' => $id, 'status' => 1, 'house_model' => 'App\Models\OfficeBuildingHouse'])->pluck('house_id')->toArray();
        //查询出对应的房子并且逾期剩余时间在2天以内的房源
        $house = OfficeBuildingHouse::whereIn('id',$houseId)->with('buildingBlock','buildingBlock.building')->where('end_track_time','>', time())->where('end_track_time', '<=', (time() + 48*3600))->get();
        $data = [];
        foreach($house as $k => $v) {
            $data[$k]['house_id'] = $v->id;
            $data[$k]['house_name'] = $v->buildingBlock->building->name. ' '. $v->buildingBlock->name .$v->buildingBlock->name_unit.$v->house_number.'室';
            $data[$k]['over_time'] = $this->time($v->end_track_time - time());
        }
        return $data;
    }

    /**
     * 说明: 获取待跟进客户数据
     *
     * @author 刘坤涛
     */
    public function waitTrackCustomer()
    {
        $id = $this->user()->id;
        //查询出维护人为该用户,结束跟进时间在2天以内的客户
        $customer = Custom::where('end_track_time','>', time())->where('end_track_time', '<=', (time() + 48*3600))->where('guardian', $id)->get();
        $data = [];
        foreach($customer as $k => $v) {
            $data[$k]['id'] = $v->id;
            $data[$k]['name'] = $v->name;
            $data[$k]['tel'] = $v->tel;
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
     * 说明: 查询登录人所属门店
     *
     * @return mixed
     * @author 刘坤涛
     */
    public function getStorefrontId()
    {
        //获取登录人id
        $id = $this->user()->id;
        //通过id查询所属门店
        $storefrontId = User::where('id', $id)->first()->ascription_store;
        return $storefrontId;
    }

    /**
     * 说明: 查询登录人同门店下的所有人员
     *
     * @author 刘坤涛
     */
    public function adoptStorefrontGetUserId()
    {
        $userId = User::where('ascription_store', $this->getStorefrontId())->pluck('id')->toArray();
        return $userId;
    }

    /**
     * 说明: 查询登录人同区域下的所有人员
     *
     * @return mixed
     * @author 刘坤涛
     */
    public function adoptAreaGetUserId($managerId = null)
    {
        //通过门店查到区域经理id
        if (!$managerId) $managerId = Storefront::where('id', $this->getStorefrontId())->first()->area_manager_id;
        //查询该区域经理下的所有门店
        $storefrontsId = Storefront::where('area_manager_id', $managerId)->pluck('id')->toArray();
        //查询所有门店下的人员
        $userId = User::whereIn('ascription_store', $storefrontsId)->pluck('id')->toArray();
        array_push($userId, $managerId);
        return $userId;
    }

    /**
     * 说明: 写字楼或客源统计数据
     *
     * @param $class
     * @param $model
     * @return array
     * @author 刘坤涛
     */
    public function statisticData($model, $class)
    {
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
                $userId = $this->adoptStorefrontGetUserId();
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
                //如果登录人是区域经理
                if ($this->user()->level == 2) {
                    $userId = $this->adoptAreaGetUserId($this->user()->id);
                } else {
                    $userId = $this->adoptAreaGetUserId();
                }
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
                $data['all_added'] = $model::count();
                break;
                default;
                break;
        }
        return $data;
    }

    /**
     * 说明: 获取房源或客户平台本周、本月环比数据
     *
     * @param $model
     * @return array
     * @author 刘坤涛
     */
    public function getRingThanData($model)
    {
        $data = [];
        //获取本月平台新增房源
        $month_num = $this->getAddedData($model,null, $this->getMonthTime());
        //获取本周平台新增房源
        $week_num  = $this->getAddedData($model, null, $this->getWeekTime());
        //获取上月新增数量
        $last_month_num = $this->getAddedData($model, null, $this->getLastMonthTime());
        //获取上周新增数量
        $last_week_num = $this->getAddedData($model, null, $this->getLastWeekTime());
        $data['month_num'] = $month_num;
        $data['week_num'] = $week_num;
        //环比上月数据
        if ($last_month_num == 0) {
            $data['ring_than_month'] = 0 . '%';
        } else {
            $data['ring_than_month'] = (int)(($month_num - $last_month_num) / $last_month_num * 100) . '%';
        }
        //环比上周数据
        if ($last_week_num == 0) {
            $data['ring_than_week'] = 0 . '%';
        } else {
            $data['ring_than_week'] = (int)(($week_num - $last_week_num) / $last_week_num * 100) . '%';
        }
        return $data;
    }

    /**
     * 说明: 根据房源或客源数量确定Y轴坐标
     *
     * @param $num
     * @return array
     * @author 刘坤涛
     */
    public function getCoordinate($num)
    {
        if ($num < 20) {
            $num = 20;
        } else {
            $end = substr($num, -1, 1);
            $value = substr($num, -2, 1);
            if ($end != 0 || $value % 2 != 0) {
                if ($value >= 0 && $value < 2 ) {
                    $num = substr_replace($num, 20, -2);
                } elseif ($value >= 2 && $value < 4) {
                    $num = substr_replace($num, 40, -2);
                } elseif ($value >= 4 && $value < 6) {
                    $num = substr_replace($num, 60, -2);
                } elseif ($value >= 6 && $value < 8)  {
                    $num = substr_replace($num, 80, -2);
                } elseif ( $value >= 8) {
                    $num = substr_replace($num, 80, -2) + 20;
                }
            }
        }
        $data[] = 0;
        $data[] = $num / 4;
        $data[] = (int)$num;
        return $data;
    }


    /**
     * 说明: 获取本周或者本月图表数据
     *
     * @param $model
     * @param $time
     * @return array
     * @author 刘坤涛
     */
    public function getChartData($model, $time)
    {
        $weekArr=array("周日","周一","周二","周三","周四","周五","周六");
        $data = [];
        $item = [];
        switch ($time) {
            //获取本周数据
            case 1:
                $date = $this->getWeekDayTime();
                foreach ($date as $v) {
                    $item[date('m-d',strtotime($v['start'])) . $weekArr[date('w',strtotime($v['start']))]] = $this->getAddedData($model,null,$v);
                }
                $num = $this->getAddedData($model,null, $this->getWeekTime());
                break;
            //获取本月数据
            case 2:
                $date = $this->getMonthDayTime();
                foreach ($date as $v) {
                    $item[date('Y-m-d',strtotime($v['start']))] = $this->getAddedData($model,null,$v);
                }
                $num = $this->getAddedData($model,null, $this->getMonthTime());
                break;
                default;
                break;
        }
        $data ['y'] =  $this->getCoordinate($num);
        $data['x'] = $item;
        return $data;
    }

    /**
     * 说明: 获取业务员业务数据
     *
     * @param null $user_id
     * @param null $time
     * @param null $name
     * @param null $ascription_store
     * @param $per_page
     * @return User
     * @author 刘坤涛
     */
    public function getUserData(
        $user_id = null,
        $time = null,
        $name = null,
        $ascription_store = null,
        $per_page
    )
    {
        $user = new User();
        if (!empty($name)) $user = $user->where('real_name', $name);
        if (!empty($ascription_store))  $user = $user->where('ascription_store', $ascription_store);
        if (!empty($user_id)) $user = $user->whereIn('id', $user_id);
        $user = $user->paginate($per_page);
        foreach ($user as $v) {
            $v->item = $this->getData($this->getDayTime(), $v->id);
        }
        if ($time) {
            foreach ($user as $v) {
                $v->item = $this->getData($time, $v->id);
            }
        }

        return $user;
    }

    /**
     * 说明: 根据登录人等级获取下属业务数据
     *
     * @param $request
     * @return User
     * @author 刘坤涛
     */
    public function getSalesmanData($request)
    {
        $id = $this->user()->id;
        //获取登录人等级
        $level = $this->user()->level;
        switch ($level) {
            //总经理查看全部人员数据
            case 1:
                $user = $this->getUserData(null,$request->time,$request->name,$request->ascription_store,$request->per_page);
                break;
            //区域经理查看其管理下的门店成员数据
            case 2:
                //获取区域经理id
                //查询区域经理管理门店的成员id
                $user_id = $this->adoptAreaGetUserId($id);
                $user = $this->getUserData($user_id,$request->time,$request->name,$request->ascription_store,$request->per_page);
                break;
            case 3:
            //店长查看店员数据
                $user_id = $this->adoptStorefrontGetUserId($this->getStorefrontId());
                $user = $this->getUserData($user_id,$request->time,$request->name,null,$request->per_page);
                break;
            case 5:
                $user_id = $this->getGroup($id);
                $user_id[] = $id;
                $user = $this->getUserData($user_id,$request->time,$request->name,null,$request->per_page);
                break;
            case 4:
                $ids[] = $id;
                $user = $this->getUserData($ids,$request->time,$request->name,null,$request->per_page);
                break;
        }
        return $user;
    }

    /**
     * 说明: 获取组下面业务员
     *
     * @param $id
     * @return mixed
     * @author 罗振
     */
    public function getGroup($id)
    {
        return User::where('group_id', $id)->pluck('id')->toArray();
    }

}