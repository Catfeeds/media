<?php

namespace App\Console\Commands;

use App\Http\Controllers\API\HousesController;
use App\Models\DwellingHouse;
use Illuminate\Console\Command;

class OldHouseDataToNew extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oldTableHouseDataToNewTable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '老房源数据导入新表';

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
        // 创建总经理
        self::oldTableHouseDataToNewTable();
    }

    /**
     * 说明: 添加总经理
     *
     * @author 罗振
     */
    public function oldTableHouseDataToNewTable()
    {
        $dwellingHouse = DwellingHouse::all();

        $dwellingHouse->map(function($v) {
            dd($v);
        });

    }
}
