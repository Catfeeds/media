<?php

namespace App\Console\Commands;

use App\Models\OfficeBuildingHouse;
use Carbon\Carbon;
use Illuminate\Console\Command;

class HouseBusineStateHandle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'houseBusineStateHandle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '房源业务状态逻辑处理';

    /**
     * AddManager constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 房源业务状态逻辑处理
        self::houseBusineStateHandle();
    }

    /**
     * 说明: 房源业务状态逻辑处理
     *
     * @author 罗振
     */
    public function houseBusineStateHandle()
    {
        // 查询所有房源数据
        $houses = OfficeBuildingHouse::get();

        foreach ($houses as $house) {
            if ($house->house_busine_state == 2) {    // 信息不明确
                // 跟进结束时间加15天小于今天,房源改为无效房源
                if (strtotime(date('Y-m-d', $house->end_track_time)) + 15*24*60*60 <= strtotime(Carbon::today())) {
                    $house->house_busine_state = 6;
                    if ($house->save()) {
                        \Log::info('房源id为:'.$house->id.'的房源业务状态修改为无效状态失败');
                    }
                }
            } elseif ($house->house_busine_state == 3) {    // 暂缓
                // 结束时间减当前时间大于60天,房源放入公盘
                if (strtotime(date('Y-m-d', $house->rent_time)) - strtotime(Carbon::today()) >= 60*24*60*60) {
                    $house->guardian = null;
                    if ($house->save()) {
                        \Log::info('房源id为:'.$house->id.'放入公盘失败');
                    }
                } elseif (strtotime(date('Y-m-d', $house->rent_time)) - strtotime(Carbon::today()) <= 60*24*60*60) {
                    $house->house_busine_state = 1;
                    if ($house->save()) {
                        \Log::info('房源id为:'.$house->id.'的房源业务状态修改为有效状态失败');
                    }
                }
            } elseif ($house->house_busine_state == 4) {    // 已租
                // 结束时间减当前时间大于60天,房源改为暂缓房源
                if (strtotime(date('Y-m-d', $house->rent_time)) - strtotime(Carbon::today()) >= 60*24*60*60) {
                    $house->house_busine_state = 2;
                    if ($house->save()) {
                        \Log::info('房源id为:'.$house->id.'的房源业务状态修改为暂缓状态失败');
                    }
                }
            } elseif ($house->house_busine_state == 7) {    // 签约
                // 结束时间减当前时间大于60天,房源改为暂缓房源
                if (strtotime(date('Y-m-d', $house->rent_time)) - strtotime(Carbon::today()) >= 60*24*60*60) {
                    $house->house_busine_state = 2;
                    if ($house->save()) {
                        \Log::info('房源id为:'.$house->id.'的房源业务状态修改为暂缓状态失败');
                    }
                }
            }
        }
    }
}
