<?php

namespace App\Console\Commands;

use App\Models\Building;
use App\Models\BuildingBlock;
use App\Models\OfficeBuildingHouse;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class houseOffice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'house:office';

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
        $this->insert();
    }
    
    // 修改融众国际
    public function update()
    {
        $res = \DB::table('tmp_houses')->where('楼盘字典', 'like', '%融众国际%')->get();
        foreach ($res as $v) {
            \DB::table('tmp_houses')->where('id', $v->id)->update([
                '楼盘字典' => '融众国际',
                '栋座位置' => '独栋'
            ]);
        }
    }

    public function insert()
    {
        $offices = \DB::table('tmp_houses')->where('用途', '写字楼')->get();
        foreach ($offices as $office) {
            // 先插入楼盘
            $building = Building::where('name', $office->楼盘字典)->first();

            if (empty($building)) {
                $building = Building::create([
                    'name' => $office->楼盘字典,
                    'type' => 2
                ]);
            }

            // 添加楼座
            if ($office->栋座位置 == 0 && $office->房号 == 0) {
                $block = array('name' => '0', 'name_unit' => '栋');
            } else {
                $tmpBlock = $this->getBlock($office->栋座位置);
                if (!empty($tmpBlock)) {
                    $block = $tmpBlock;
                } else {
                    $block = array('name' => '独', 'name_unit' => '栋');
                }
            }

            // 先插入楼盘
            $buildingBlock = BuildingBlock::where(['building_id' => $building->id, 'name' => $block['name']])->first();

            if (empty($buildingBlock)) {
                $buildingBlock = BuildingBlock::create([
                    'building_id' => $building->id,
                    'name' => $block['name'],
                    'name_unit' => $block['name_unit']
                ]);
            }

            // 添加房源
            // 处理装修
            if ($office->装修 == '豪装') $office->装修 = 1;
            if ($office->装修 == '精装') $office->装修 = 2;
            if ($office->装修 == '中装') $office->装修 = 3;
            if ($office->装修 == '简装') $office->装修 = 4;
            if ($office->装修 == '毛坯') $office->装修 = 5;
            if ($office->装修 == '清水') $office->装修 = null;
            // 处理联系人
            $link = array();
            if (!empty($office->业主) && $office->手机 !=null) array_push($link, ['name' => $office->业主, 'tel' => $office->手机]);
            if (!empty($office->联系人) && $office->联系 !=null) array_push($link, ['name' => $office->联系人, 'tel' => $office->联系]);

            $data = [
                // 楼座
                'building_blocks_id' => $buildingBlock->id,
                // 房号
                'house_number' => $office->房号,
                // 楼层
                'floor' => $office->楼层,
                // 面积
                'constru_acreage' => $office->面积,
                // 装修
                'renovation' => $office->装修,
                // 业主 手机 联系人 联系
                'owner_info' => $link
            ];

            // 处理房型
            $houseType = explode('-', $office->房型);
            if (count($houseType) ==3 ) {
                if ($houseType[0] == '' || $houseType[0] == ' ') $houseType[0] = null;
                if ($houseType[1] == '' || $houseType[1] == ' ') $houseType[1] = null;

                $tmp = ['room' => (int)$houseType[0], 'hall' => (int)$houseType[1]];
                $data = array_merge($data, $tmp);
            }

            OfficeBuildingHouse::create($data);

        }
    }

    public function getBlock($str)
    {
        $dong = mb_strstr($str, '栋', true);
        $zuo = mb_strstr($str, '座', true);

        if (!empty($dong)) return array('name' => $dong, 'name_unit' => '栋');
        if (!empty($zuo)) return array('name' => $dong, 'name_unit' => '座');

        return array();

    }

}
