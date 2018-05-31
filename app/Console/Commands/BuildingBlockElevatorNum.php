<?php

namespace App\Console\Commands;

use App\Models\BuildingBlock;
use Illuminate\Console\Command;

class BuildingBlockElevatorNum extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'buildingBlockElevatorNum';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重写楼坐电梯总数量';

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
        // 电梯总数量
        self::elevatorNum();
    }


    public function elevatorNum()
    {
        // 获取所有楼座信息
        $buildingBlocks = BuildingBlock::get();

        foreach ($buildingBlocks as $v) {
            $v->elevator_num = (int)$v->passenger_lift + (int)$v->cargo_lift + (int)$v->president_lift;
            if (empty($v->save())) {
                \Log::error('id为:'.$v->id.'电梯总数量更新失败');
            }
        }
    }
}
