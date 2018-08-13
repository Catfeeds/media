<?php

namespace App\Console\Commands;

use App\Models\Building;
use App\Models\BuildingKeywords;
use Fukuball\Jieba\Finalseg;
use Fukuball\Jieba\Jieba;
use Illuminate\Console\Command;
use Overtrue\LaravelPinyin\Facades\Pinyin;

class BuildingKeyword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'buildingKeyword';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '楼盘关键字';

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
        // 楼盘关键字
        self::buildingKeyword();
    }

    /**
     * 说明: 楼盘关键字添加
     *
     * @author 罗振
     */
    public function buildingKeyword()
    {
        ini_set('memory_limit', '1024M');
        Jieba::init();
        Finalseg::init();

        $buildings = Building::with('block', 'area.city')->get();

        foreach ($buildings as $key => $v) {
            $buildingName = $v->name;   // 楼盘名
            $blockName = empty($v->block)?'':$v->block->name;   // 商圈名
            $areaName = $v->area->name; // 区域名
            $cityName = $v->area->city->name;   // 城市名
            $string = $buildingName.$blockName.$areaName.$cityName;

            // 切词之后的字符串
            $jbArray = Jieba::cutForSearch($string);

            // 汉子转拼音
            $pyJbArray = array();
            foreach ($jbArray as $value) {
                $pyJbArray[] = preg_replace('# #', '', Pinyin::sentence($value));
            }

            // 字符串长度
            $length = mb_strlen($string, 'utf-8');
            $array = [];
            for ($i=0; $i<$length; $i++) {
                $array[] = mb_substr($string, $i, 1, 'utf-8');
            }

            // 楼盘名
            $array[] = $buildingName;

            // 汉子转拼音
            $pyArray = array();
            foreach ($array as $val) {
                $pyArray[] = preg_replace('# #', '', Pinyin::sentence($val));
            }

            $endString = array_unique(array_merge($array, $jbArray, $pyArray, $pyJbArray));

            $string = implode(' ', $endString);

            $res = BuildingKeywords::create([
                'building_id' => $v->id,
                'keywords' => $string
            ]);
            if (empty($res)) \Log::error('id为:'.$v->id.'的楼盘关键词添加失败');
        }
    }
}
