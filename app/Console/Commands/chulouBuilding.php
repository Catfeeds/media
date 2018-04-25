<?php

namespace App\Console\Commands;

use App\Models\Area;
use App\Models\Block;
use App\Models\Building;
use App\Models\BuildingBlock;
use App\Models\House;
use App\Models\OfficeBuildingHouse;
use App\Services\HousesService;
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
        $houses = DB::connection('chulou')->table('data_house_rent_in_info')->get();

        foreach ($houses as $house) {
            // 拿到楼盘id
            $oldBuilding = DB::connection('chulou')->table('data_building_info')->where('guid', $house->building_guid)->first();
            $buildingId = Building::where('name', $oldBuilding->name)->first()->id;

            // 处理楼座
            // 楼座
            // 如果为空 插入独栋
            // 如果
            $houseService = new HousesService();

            $blockData = $houseService->blockForm($house->building_num);

            $block = BuildingBlock::where('building_id', $buildingId)->first();
            if (empty($block)) {
                $block = BuildingBlock::create([
                    'name' => $blockData['name'],
                    'name_unit' => $blockData['name_unit'],
                    'building_id' => $buildingId
                ]);
            }

            $owner = array(json_encode(array('name' => $house->signing_party, 'tel' => $house->signing_party_telnum), JSON_UNESCAPED_UNICODE));

            $house = OfficeBuildingHouse::create([
                // 楼座
                'building_block_id' => $block->id,
                // 房号
                'house_number' => $house->house_num,
                // 楼层
                'floor' => (int)$house->floor_num,
                // 面积
                'constru_acreage' => $house->total_acreage,
                // 业主信息
                'owner_info' => $owner,
                // 房源业务状态: 1: 有效 2: 暂缓 3: 已租 4: 收购 5: 托管 6: 无效
                'house_busine_state' => 1
            ]);
            // 添加识别号
            $house->house_identifier = $houseService->setHouseIdentifier('Z', $house->id);
            $house->save();
        }
    }
}
