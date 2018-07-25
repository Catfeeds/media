<?php

namespace App\Console\Commands;

use App\Models\Block;
use App\Models\BlockLocation;
use Illuminate\Console\Command;

class BlockLocationUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'up:blockLocationUp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '维护商圈经纬度';

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
        $block_locations = [];
        $blocks = Block::all();
        foreach ($blocks as $block) {
            if (BlockLocation::where(['block_id' => $block->id])->get()->count()) {
                $json = self::curl('http://api.map.baidu.com/geocoder/v2/?address=' . $block->name . '&output=json&ak=sQSt44MuNRcHxRGeKICqkzwoSiLrStQB&c&city=武汉');
                array_push($block_locations, ['block_id' => $block->id, 'x' => $json->result->location->lng, 'y' => $json->result->location->lat]);
            }
        }
        // 拿到经纬度
        dump($block_locations);
    }

    public function curl($url)
    {
        $curlobj = curl_init();
        curl_setopt($curlobj, CURLOPT_URL, $url);
        // curl_setopt($curlobj, CURLOPT_HEADER, 0);
        curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, 1);
        // 验证
        curl_setopt($curlobj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlobj, CURLOPT_SSL_VERIFYHOST, 0);
        $res = curl_exec($curlobj);
        $data = json_decode($res);
        return $data;
        curl_close($curlobj);
    }
}
