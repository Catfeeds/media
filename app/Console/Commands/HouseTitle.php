<?php

namespace App\Console\Commands;

use App\Models\OfficeBuildingHouse;
use App\Services\HousesService;
use Illuminate\Console\Command;

class HouseTitle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'houseTitle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '老数据生成房源';

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
        // 老数据生成房源
        self::houseTitle();
    }

    /**
     * 说明: 写字楼老数据生成房源
     *
     * @author 罗振
     */
    public function houseTitle()
    {
        $houseService = new HousesService();

        // 获取所有房源
        $houses = OfficeBuildingHouse::all();
        foreach ($houses as $house) {
            $title = $houseService->getTitle($house);
            $totalPrice = $house->unit_price * $house->constru_acreage;

            $house->title = $title;
            $house->total_price = $totalPrice;
            if (!$house->save()) {
                \Log::error($house->id.'生成房源标题失败');
            }
        }
    }
}
