<?php

namespace App\Console\Commands;

use App\Models\Building;
use Illuminate\Console\Command;

class BuildingImg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:buildingImg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '将louwang数据库的楼盘图加到media中';

    /**
     * Create a new command instance.
     *
     * @return void
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
        // 查询出media中的所有building
        $buildings = Building::all();
        foreach ($buildings as $building) {
            // 取出louawng的对应building
            $tmpBuilding = \DB::connection('chulou')->table('data_building_info')->where('name', $building->name)->first();
            // 没有图片的跳过
            if (empty($tmpBuilding->banner)) continue;
            // 如果有图片进行处理
            $banner = json_decode($tmpBuilding->banner, true);
            $imgs = array_values($banner);
            // 存入img中
            $building->album = $imgs;
            $building->save();
        }
    }
}
