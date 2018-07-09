<?php

namespace App\Console\Commands;

use App\Models\Block;
use App\Models\BlockLocation;
use Illuminate\Console\Command;

class GetFtxBlock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getFtxBlock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取房天下商圈地理位置';

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
        self::getFtxBlock();
    }

    /**
     * 说明: 获取房天下商圈地理位置
     *
     * @author 罗振
     */
    public function getFtxBlock()
    {
        // 获取所有房天下商圈地址位置数据
        $res = curl('http://192.168.0.188:8866/mock/5b19f300152f4405081fd865/map/detailArea', 'GET')->data;

        // 获取商圈所有数据
        $blocks = Block::withCount('building')->get();
        foreach ($blocks as $k => $v) {
            foreach ($res as $val) {
                if (stristr($v->name, $val->name)) {
                    $addBlockLocation = BlockLocation::create([
                        'block_id' => $v->id,
                        'x' => $val->x,
                        'y' => $val->y,
                        'scope' => $val->baidu_coord,
                        'building_num' => $v->building_count
                    ]);
                    if (empty($addBlockLocation)) \Log::error('商圈名为:'.$v->name.'的商圈添加失败');
                }
            }
        }
    }
}
