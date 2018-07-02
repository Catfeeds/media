<?php

namespace App\Services;

use App\Models\Custom;
use App\Models\OfficeBuildingHouse;

class MonthlyStatisticsService
{
    // 处理年份
    public function getTime(
        $year
    )
    {
        if(empty($year)){
            $now = time();
            $year = date("Y",$now);
        }

        // 如果选定年份等于当前年份
        if ($year == date('Y')) {
            $endFor = (int)date('m');
        } else {
            $endFor = 12;
        }

        $times = array();
        for ($i=1;$i<=$endFor;$i++) {
            $times[$i]['begin'] = date('Y-m-d H:i:s', mktime(0,0,0,$i,1,$year));
            $times[$i]['end'] = date('Y-m-d H:i:s', mktime(23,59,59,($i+1),0,$year));
        }

        return $times;
    }

    // 通过年份获取来源为58,安居客,赶集的房源及客户数量
    public function getSourceCount(
        $times
    )
    {
        $datas = array();
        foreach ($times as $time) {
            // 设置key
            $key = date("Y-m",strtotime($time['begin']));
            // 来源为58,安居客,赶集的房源
            $datas[$key]['source_58_house'] = OfficeBuildingHouse::where(['source' => 6])->whereBetween('created_at', [$time['begin'], $time['end']])->count();
            $datas[$key]['source_ajk_house'] = OfficeBuildingHouse::where(['source' => 5])->whereBetween('created_at', [$time['begin'], $time['end']])->count();
            $datas[$key]['source_gj_house'] = OfficeBuildingHouse::where(['source' => 4])->whereBetween('created_at', [$time['begin'], $time['end']])->count();
            // 来源为58,安居客,赶集的客户
            $datas[$key]['source_58_customer'] = Custom::where(['source' => 6])->whereBetween('created_at', [$time['begin'], $time['end']])->count();
            $datas[$key]['source_ajk_customer'] = Custom::where(['source' => 5])->whereBetween('created_at', [$time['begin'], $time['end']])->count();
            $datas[$key]['source_gj_customer'] = Custom::where(['source' => 4])->whereBetween('created_at', [$time['begin'], $time['end']])->count();
            // 总数
            $datas[$key]['total_count'] = array_sum($datas[$key]);
        }

        return $datas;
    }
    

}