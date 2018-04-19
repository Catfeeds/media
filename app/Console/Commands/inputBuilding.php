<?php

namespace App\Console\Commands;

use App\Models\Building;
use App\Models\BuildingBlock;
use App\Models\House;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class inputBuilding extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'input:building';

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
        $this->updateQi();
    }

    /**
     * 说明：将包含期的数据'期'加到楼盘上
     *
     * @author jacklin
     */
    public function updateQi()
    {
        $res = \DB::table('tmp_houses')->get();
        $count = 0;
        foreach ($res as $v) {
            // 处理楼栋
            $block = $v->栋座位置;
            $arr = explode('期', $block);
            if (count($arr) == 2) {
                if($arr[1]=='') $arr[1] = '独';
                \DB::table('tmp_houses')->where('id', $v->id)->update([
                    '栋座位置' => $arr[1],
                    '楼盘字典' => $v->楼盘字典 . $this->numberUnit($arr[0]) . '期'
                ]);
                $count++;
            }
        }
        dump($count);
    }

    public function addBuilding()
    {
        $res = \DB::table('tmp_houses')->get();
        foreach ($res as $v) {
            $buildingName = $v->楼盘字典;
            $building = Building::where('name', $buildingName)->first();
            if (!empty($building)) continue;
            if ($v->type === '住宅') $type = 1;
            if ($v->type === '写字楼') $type = 2;
            if ($v->type === '商铺') $type = 3;
            Building::create([
                'name' =>  $v->楼盘字典,
                'type' => $type
            ]);
        }
    }

    public function addBlocks()
    {
        // 如果不是正常格式 直接跳过
        $res = \DB::table('tmp_houses')->get();
        foreach ($res as $v) {
            $buildingName = $v->楼盘字典;
            $building = Building::where('name', $buildingName)->first();
            // 10栋1单元 独栋 2期4栋 B座
            // 处理楼栋
            $block = $v->栋座位置;
            $block = $this->blockForm($block);
            $res = BuildingBlock::where('name', $block['name'])->first();
            if (empty($res)) {
                // 如果没有这个楼栋 楼栋
                $res = BuildingBlock::create([
                    'building_id' => $building->id,
                    'name' => $block['name']
                ]);
            }
        }
    }

    public function addHouse()
    {
        $res = \DB::table('tmp_houses')->get();
        foreach ($res as $v) {
            $buildingName = $v->楼盘字典;
            $building = Building::where('name', $buildingName)->first();
            // 10栋1单元 独栋 2期4栋 B座
            // 处理楼栋
            $block = $v->栋座位置;
            $block = $this->blockForm($block);
            $block = BuildingBlock::where('name', $block['name'])->first();

//            if ($v->用途 === '住宅') {
//                House::create([
//                    'building_blocks_id' => $block->id,
//                    'house_number' => $v->房号,
//                ]);
//            } elseif($v->用途 === '写字楼') {
//                House::create([
//                    'building_blocks_id' => $block->id,
//                    'house_number' => $v->房号,
//                ]);
//            } elseif($v->用途 === '商铺') {
//                House::create([
//                    'building_blocks_id' => $block->id,
//                    'house_number' => $v->房号,
//                ]);
//            }
        }
    }

    /**
     * 说明：通过表格楼栋分割成标准数据
     *
     * @param $str
     * @return array
     * @author jacklin
     */
    public function blockForm($str)
    {
        $data = array();
        // 如果有 '栋'
        $dong = mb_strstr($str, '栋', true);
        if (!empty($dong)) {
            $data['name'] = $dong;
            $data['name_unit'] = '栋';
            $str = explode('栋', $str)[1];
        } else {
            $dong = mb_strstr($str, '座', true);
            $data['name'] = $dong;
            $data['name_unit'] = '座';
            $str = explode('座', $str)[1];
        }

        $danyuan = mb_strstr($str, '单元', true);
        if (!empty($danyuan)) {
            $data['unit'] = $danyuan;
            $data['unit_unit'] = '单元';
        } else {
            $danyuan = mb_strstr($str, '门', true);
            if (!empty($danyuan)) {
                $data['unit'] = $danyuan;
                $data['unit_unit'] = '门';
            }
        }
        return $data;
    }

    /**
     * 说明：标准化数字
     *
     * @param $num
     * @return string
     * @author jacklin
     */
    public function numberUnit($num)
    {
        if ($num == '一') $num = '1';
        if ($num == '二') $num = '2';
        if ($num == '三') $num = '3';
        if ($num == '四') $num = '4';
        if ($num == '五') $num = '5';
        if ($num == '六') $num = '6';
        if ($num == '七') $num = '7';
        if ($num == '八') $num = '8';
        if ($num == '九') $num = '9';

        return $num;

    }
}
