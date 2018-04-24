<?php

namespace App\Console\Commands;

use App\Models\Area;
use App\Models\Block;
use App\Models\Building;
use Illuminate\Console\Command;

class chulouBlock extends Command
{
    /**
     * 导入楚楼网的商圈数据
     *
     * @var string
     */
    protected $signature = 'data:chuLouBlock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $areas = \DB::connection('chulou')->table('data_area_info')->get();

        foreach ($areas as $area) {
            // 区
            if ($area->type == 1 ) {
                Area::create([
                    'name' => $area->name,
                    'city_id' => 1
                ]);
            }
            // 商圈
            if ($area->type == 2 ) {
                $areaId = Area::where('name',$area->parent_name)->first()->id;
                Block::create([
                    'name' => $area->name,
                    'area_id' => $areaId
                ]);
            }
        }

        $buildings = \DB::connection('chulou')->table('data_building_info')->get();

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
                'gps' => json_decode('[' . $building->gps . ']')
            );
            Building::create($data);
        }
    }
}
