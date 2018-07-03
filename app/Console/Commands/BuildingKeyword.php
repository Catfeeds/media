<?php

namespace App\Console\Commands;

use App\Models\Building;
use App\Models\BuildingKeywords;
use Fukuball\Jieba\Finalseg;
use Fukuball\Jieba\Jieba;
use Illuminate\Console\Command;

class BuildingKeyword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'buildingKeyword';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '楼盘关键字';

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
        // 楼盘关键字
        self::buildingKeyword();
    }

    /**
     * 说明: 楼盘关键字添加
     *
     * @author 罗振
     */
    public function buildingKeyword()
    {
        ini_set('memory_limit', '1024M');
        Jieba::init();
        Finalseg::init();

        $buildings = Building::with('block', 'area.city')->get();

        foreach ($buildings as $key => $v) {
            $buildingName = $v->name;   // 楼盘名
            $blockName = empty($v->block)?'':$v->block->name;   // 商圈名
            $areaName = $v->area->name; // 区域名
            $cityName = $v->area->city->name;   // 城市名

            // 关键词拼接
            $string = implode(' ', array_unique(Jieba::cutForSearch($buildingName.$blockName.$areaName.$cityName)));

            $res = BuildingKeywords::create([
                'building_id' => $v->id,
                'keywords' => $string
            ]);
            if (empty($res)) \Log::error('id为:'.$v->id.'的楼盘关键词添加失败');
        }
    }
}
