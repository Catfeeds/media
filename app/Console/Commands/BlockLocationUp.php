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
        $blocks = Block::all();
        $count1 = 0;
        $count2 = 0;
        $this->info('开始执行维护商圈经纬度...');
        foreach ($blocks as $block) {
            if (!BlockLocation::where(['block_id' => $block->id])->get()->count()) {
                if (empty($block->id) || empty($block->name)) {
                    $this->info('商圈id获取失败');
                    ++$count2;
                    continue;
                }
                $name = $block -> name;
                $name = $name === '中南' ? $name . '路' : $name;
                $name = $name === '宝通寺' ? '宝通禅寺' : $name;
                $json = self::curl('http://api.map.baidu.com/geocoder/v2/?address=' . $name . '&output=json&ak=sQSt44MuNRcHxRGeKICqkzwoSiLrStQB&c&city=武汉');
                if ($json->status) {
                    $this->info('商圈 - ' . $block->name . ' - 坐标信息获取失败');
                    ++$count2;
                    continue;
                }
                $res = BlockLocation::create(['block_id' => $block->id, 'x' => $json->result->location->lng, 'y' => $json->result->location->lat]);
                if (empty($res)) {
                    $this->info($block->name . ' - 坐标信息添加失败');
                    ++$count2;
                } else {
                    $this->info($block->name . ' - 坐标信息添加成功');
                    ++$count1;
                }
            }
        }
        $this->info('结束');
        $this->info('共添加成功' . $count1 . '个');
        $this->info('失败' . $count2 . '个');

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
