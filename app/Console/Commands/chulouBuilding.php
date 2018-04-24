<?php

namespace App\Console\Commands;

use App\Models\Area;
use App\Models\Block;
use App\Models\Building;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class chulouBuilding extends Command
{
    /**
     * 将楚楼网的数据库中的楼盘导入到中介管理系统
     *
     * @var string
     */
    protected $signature = 'data:chuLouBuilding';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '将楚楼网的数据库中的楼盘导入到中介管理系统';

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
        $buildings = DB::connection('chulou')->table('data_building_info')->get();

        foreach ($buildings as $building) {
            // 拿到城区
            $area = Area::where('name', $building->area)->first();
            // 拿到商圈
            $block = Block::where('name', $building->block)->first();
            $data = array(
                'name' => $building->name,
                'type' => 2,
                'area_id' => $area->id,
                'block_id' => $block->id,
                'address' => $building->address,
                'acreage' => $building->acreage,
                'gps' => '[' . $building->gps . ']'
            );
            Building::create($data);
        }
    }
}
