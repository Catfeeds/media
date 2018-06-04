<?php

namespace App\Services;



use App\Models\Custom;
use App\Models\HouseImgRecord;
use App\Models\OfficeBuildingHouse;
use App\Models\OwnerViewRecord;
use App\Models\Track;

class HomePagesService
{
    /**
     * 说明: 获取对应的时间日期
     *
     * @param $time
     * @return mixed
     * @author 刘坤涛
     */
    public function getTime($time)
    {
        $year = date('Y'); //当前年份
        $month = date('m');//当前月份
        $day = date('d');  //当前日期
        switch ($time) {
            case 1:
                //计算今天0:0:0 和 23:59:59秒的时间戳
                $start = mktime(0, 0, 0, $month, $day, $year);
                $end = mktime(23, 59, 59, $month, $day, $year);
                break;
            case 2:
                //计算本周星期一0:0:0 和 星期天23:59:59秒的时间戳
                $start = strtotime('Sunday -6 day',strtotime($year.'-'.$month.'-'.$day));
                $end = strtotime('Monday 7 day',strtotime($year.'-'.$month.'-'.$day)) - 1;
                break;
            case 3:
                //计算本月1号 0:0:0 和本月最后一天23:59:59秒的时间戳
                $start = mktime(0,0,0,$month,1,$year);
                $end = mktime(23,59,59,$month,date('t'), $year);
                break;
            case 4:
                //如果是上半年
                //计算1月1号 0:0:0 和6月30号23:59:59的时间戳
                if ($month <= 6) {
                    $start = mktime(0,0,0,1,1,$year);
                    $end = mktime(23,59,59,6,30,$year);
                } else {
                    //如果是下半年
                    //计算7月1号 和12月31号23:59:59秒的时间戳
                    $start = mktime(0,0,0,7,1,$year);
                    $end = mktime(23,59,59,12,31,$year);
                }
                break;
                default;
                break;
        }
        $date['start'] = date('Y-m-d H:i:s', $start);
        $date['end'] = date('Y-m-d H:i:s', $end);
        return $date;
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
            $hour = floatval(($timestamps - $day) * 24);
            $time = $day . '天' . $hour . '小时';
        } else {
            $time = (int)$timestamps * 24 . '小时';
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
        $date = $this->getTime($time);
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


    public function waitTrackHouse($id)
    {
        //查询该用户的待跟进房源并且逾期时间在2天以内的房源ID
        $houseId = OwnerViewRecord::where(['user_id' => $id, 'status' => 1, 'house_model' => 'App\Models\OfficeBuildingHouse'])->pluck('house_id')->toArray();
        //查询出对应的房子
        $house = OfficeBuildingHouse::whereIn('id',$houseId)->with('buildingBlock','buildingBlock.building')->where('end_track_time','<', (time() + 48*3600))->get();
        $data = [];
        foreach($house as $k => $v) {
            $data[$k]['house_id'] = $v->id;
            $data[$k]['name'] = $v->buildingBlock->building->name. ' '. $v->buildingBlock->name .$v->buildingBlock->name_unit.$v->house_number.'室';
            $data[$k]['time'] = $this->time($v->end_track_time - time());
        }
        return $data;
    }





    
}