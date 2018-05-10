<?php

namespace App\Console\Commands;

use App\Models\OfficeBuildingHouse;
use App\Models\ShopsHouse;
use App\Services\HousesService;
use Illuminate\Console\Command;

class OldTableHouseDataToNewTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oldTableHouseDataToNewTable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '老房源数据导入新表';

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
        // 创建总经理
        self::oldTableHouseDataToNewTable();
    }

    /**
     * 说明: 写字楼,商铺房源加入单价总价
     *
     * @author 罗振
     */
    public function oldTableHouseDataToNewTable()
    {
        \DB::beginTransaction();
        try {
            $housesService = new HousesService();

            $officeBuildingHouse = OfficeBuildingHouse::all();
            $officeBuildingHouse->map(function($v) use($housesService) {
                if (!empty($v->rent_price) && !empty($v->rent_price_unit) && !empty($v->constru_acreage)) {
                    $unit_price = $housesService->getPrice($v->rent_price, $v->rent_price_unit, $v->constru_acreage);

                    $v->unit_price = $unit_price;    // 单价
                    if (!$v->save()) {
                        \Log::info($v->id.'数据修改失败');
                    }
                }
            });

            $shopsHouse = ShopsHouse::all();
            $shopsHouse->map(function($v) use($housesService) {
                if (!empty($v->rent_price) && !empty($v->rent_price_unit) && !empty($v->constru_acreage)) {
                    $unit_price = $housesService->getPrice($v->rent_price, $v->rent_price_unit, $v->constru_acreage);

                    $v->unit_price = $unit_price;    // 单价
                    if (!$v->save()) {
                        \Log::info($v->id.'数据修改失败');
                    }
                }
            });

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::error($exception->getMessage());
            return false;
        }

    }
}
