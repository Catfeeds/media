<?php

namespace App\Console\Commands;

use App\Models\Custom;
use App\Models\DwellingHouse;
use App\Models\OfficeBuildingHouse;
use App\Models\ShopsHouse;
use App\Models\Track;
use Illuminate\Console\Command;

class HouseAndCustomToPublic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'HouseAndCustomToPublic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '房源,客户私盘移入公盘';

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
        //
        $this->HouseAndCustomToPublic();
    }

    public function houseAndCustomToPublic()
    {
        \DB::beginTransaction();
        try {
            // 房源
            // 1. 住宅房源
            $dwellingHouse = DwellingHouse::all();
            $dwellingTemps = array();
            foreach ($dwellingHouse as $k => $v) {
                // 查询最后一条
                $temp = Track::where([
                    'house_model' => 'App\Models\DwellingHouse',
                    'house_id' => $v->id,
                    'user_id' => $v->guardian,
                ])->orderBy('id', 'desc')
                    ->first();

                if (empty($temp)) {
                    $dwellingTemps[$k] = $v->id;
//                    $dwellingTemps['toPublic'] = true;
                }

                $dwellingTemps[$k] = $temp;
            }
            dd($dwellingTemps);

            foreach ($dwellingTemps as $dwellingTemp) {
                if (empty($dwellingTemp)) {
                    // 丢入公盘
                    $res = DwellingHouse::where('id', $v->id)->update(['guardian' => null]);
                    if (!$res) {
                        throw new \Exception('id为:'.$v->id.'的住宅房源加入公盘失败');
                    }
                } else {
                    // 判断时间
                    if (strtotime($dwellingTemp->created_at) + config('setting.house_to_public')*60*60*24 > strtotime(date('Ymd'))) {
                        $res = DwellingHouse::where('id', $v->id)->update(['guardian' => null]);
                        if (!$res) {
                            throw new \Exception('id为:'.$v->id.'的住宅房源加入公盘失败');
                        }
                    }
                }
            }

            // 2. 商铺房源
            $shopsHouse = ShopsHouse::all();
            foreach ($shopsHouse as $v) {
                // 查询最后一条
                $shopsTemp = Track::where([
                    'house_model' => 'App\Models\ShopsHouse',
                    'house_id' => $v->id,
                    'user_id' => $v->guardian,
                ])->first();
            }

            if (empty($shopsTemp)) {
                // 丢入公盘
                $res = ShopsHouse::where('id', $v->id)->update(['guardian' => null]);
                if (!$res) {
                    throw new \Exception('id为:'.$v->id.'的商铺房源加入公盘失败');
                }
            } else {
                // 判断时间
                if (strtotime($shopsTemp->created_at) + config('setting.house_to_public')*60*60*24 > strtotime(date('Ymd'))) {
                    $res = ShopsHouse::where('id', $v->id)->update(['guardian' => null]);
                    if (!$res) {
                        throw new \Exception('id为:'.$v->id.'的商铺房源加入公盘失败');
                    }
                }
            }

            // 3. 写字楼
            $officeBuildingHouse = OfficeBuildingHouse::all();
            foreach ($officeBuildingHouse as $v) {
                // 查询最后一条
                $officeTemp = Track::where([
                    'house_model' => 'App\Models\OfficeBuildingHouse',
                    'house_id' => $v->id,
                    'user_id' => $v->guardian,
                ])->first();
            }

            if (empty($officeTemp)) {
                // 丢入公盘
                $res = OfficeBuildingHouse::where('id', $v->id)->update(['guardian' => null]);
                if (!$res) {
                    throw new \Exception('id为:'.$v->id.'的写字楼房源加入公盘失败');
                }
            } else {
                // 判断时间
                if (strtotime($officeTemp->created_at) + config('setting.house_to_public')*60*60*24 > strtotime(date('Ymd'))) {
                    $res = OfficeBuildingHouse::where('id', $v->id)->update(['guardian' => null]);
                    if (!$res) {
                        throw new \Exception('id为:'.$v->id.'的写字楼房源加入公盘失败');
                    }
                }
            }

            // 4. 客户
            $customs = Custom::all();
            foreach ($customs as $v) {
                $customTemp = Track::where([
                    'custom_id' => $v->id,
                    'user_id' => $v->guardian,
                ])->first();
            }

            if (empty($customTemp)) {
                // 丢入公盘
                $res = Custom::where('id', $v->id)->update(['guardian' => null]);
                if (!$res) {
                    throw new \Exception('id为:'.$v->id.'的客户加入公盘失败');
                }
            } else {
                // 判断时间
                if (strtotime($officeTemp->created_at) + config('setting.custom_to_public')*60*60*24 > strtotime(date('Ymd'))) {
                    $res = Custom::where('id', $v->id)->update(['guardian' => null]);
                    if (!$res) {
                        throw new \Exception('id为:'.$v->id.'的客户加入公盘失败');
                    }
                }
            }

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::error($exception->getMessage());
            return false;
        }
    }
}