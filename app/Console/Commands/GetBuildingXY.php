<?php

namespace App\Console\Commands;

use App\Models\Building;
use Illuminate\Console\Command;

class GetBuildingXY extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getBuildingXY';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '将楼盘的gps分割为x,y';

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
        self::getBuildingXY();
    }

    /**
     * 说明: 将楼盘的gps分割为x,y
     *
     * @author 罗振
     */
    public function getBuildingXY()
    {
        $buildings = Building::all();

        foreach ($buildings as $building) {
            $building->x = $building->gps[0];
            $building->y = $building->gps[1];
            if (!$building->save()) \Log::info('id为:'.$building->id.'的楼盘gps分割失败');
        }
    }
}
