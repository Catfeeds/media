<?php

namespace App\Console\Commands;

use App\Models\Area;
use App\Models\AreaLocation;
use Illuminate\Console\Command;

class GetAreaGps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getAreaGps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取区域gps及周边地理位置';

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
        self::getAreaGps();
    }

    /**
     * 说明: 获取区域gps及周边地理位置
     *
     * @author 罗振
     */
    public function getAreaGps()
    {
        // 获取区域gps及周边地理位置
        $res = curl('http://192.168.0.188:8866/mock/5b19f300152f4405081fd865/map/getRegionList', 'GET')->data;

        $areas = Area::where([])->with('building')->get();
        foreach ($areas as $k => $v) {
            foreach ($res as $val) {
                if (stristr($v->name, $val->name)) {
                    $addAreaLocation = AreaLocation::create([
                        'area_id' => $v->id,
                        'x' => $val->x,
                        'y' => $val->y,
                        'scope' => $val->baidu_coord,
                        'building_num' => $v->building->count()
                    ]);
                    if (empty($addAreaLocation)) \Log::error('区域名为:'.$v->name.'的区域添加失败');
                }
            }
        }

    }
}
