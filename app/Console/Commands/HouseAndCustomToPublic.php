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

    /**
     * 说明: 房源,客户加入公盘
     *
     * @return bool
     * @author 罗振
     */
    public function houseAndCustomToPublic()
    {
        \DB::beginTransaction();
        try {
            // 计算一个月的时间戳
            $month = config('setting.house_to_public')*60*60*24;


            // 房源
            // 1. 住宅房源
            $dwellingHouse = DwellingHouse::all();
            $dwellingTemps = array();
            foreach ($dwellingHouse as $k => $v) {
                // 查询最后一条
                $temp = Track::where([
                    'house_model' => 'App\Models\DwellingHouse',
                    'house_id' => $v->id,
                    'user_id' => $v->guardian
                ])->orderBy('id', 'desc')
                ->first();

                if (empty($temp)) {
                    $dwellingTemps[$k]['id'] = $v->id;
                    // 房源创建时间
                    $dwellingTemps[$k]['createTime'] = $v->created_at->toDateString();
                    $dwellingTemps[$k]['toPublic'] = true;
                } else {
                    $temp->toPublic = false;
                    $dwellingTemps[$k] = $temp;
                }
            }

            foreach ($dwellingTemps as $dwellingTemp) {
                if ($dwellingTemp['toPublic'] == true) {
                    // 判断房源添加时间
                    if (strtotime($dwellingTemp['createTime']) + $month < strtotime(date('Ymd'))) {
                        $res = DwellingHouse::where('id', $dwellingTemp['id'])->update(['guardian' => null]);
                        if (!$res) {
                            throw new \Exception('id为:'.$dwellingTemp['id'].'的住宅房源加入公盘失败');
                        }
                    }
                } else {
                    // 判断跟进添加时间
                    if (strtotime($dwellingTemp->created_at->toDateString()) + $month < strtotime(date('Ymd'))) {
                        $res = DwellingHouse::where('id', $dwellingTemp->id)->update(['guardian' => null]);
                        if (!$res) {
                            throw new \Exception('id为:'.$dwellingTemp->id.'的住宅房源加入公盘失败');
                        }
                    }
                }
            }

            // 2. 商铺房源
            $shopsHouse = ShopsHouse::all();
            $shopsTemps = array();
            foreach ($shopsHouse as $k => $v) {
                // 查询最后一条
                $temp = Track::where([
                    'house_model' => 'App\Models\ShopsHouse',
                    'house_id' => $v->id,
                    'user_id' => $v->guardian
                ])->orderBy('id', 'desc')
                    ->first();

                if (empty($temp)) {
                    $shopsTemps[$k]['id'] = $v->id;
                    // 房源创建时间
                    $shopsTemps[$k]['createTime'] = $v->created_at->toDateString();
                    $shopsTemps[$k]['toPublic'] = true;
                } else {
                    $temp->toPublic = false;
                    $shopsTemps[$k] = $temp;
                }
            }

            foreach ($shopsTemps as $shopsTemp) {
                if ($shopsTemp['toPublic'] == true) {
                    // 判断房源添加时间
                    if (strtotime($shopsTemp['createTime']) + $month < strtotime(date('Ymd'))) {
                        $res = ShopsHouse::where('id', $shopsTemp['id'])->update(['guardian' => null]);
                        if (!$res) {
                            throw new \Exception('id为:'.$shopsTemp['id'].'的商铺房源加入公盘失败');
                        }
                    }
                } else {
                    // 判断跟进添加时间
                    if (strtotime($shopsTemp->created_at->toDateString()) + $month < strtotime(date('Ymd'))) {
                        $res = ShopsHouse::where('id', $shopsTemp->id)->update(['guardian' => null]);
                        if (!$res) {
                            throw new \Exception('id为:'.$shopsTemp->id.'的商铺房源加入公盘失败');
                        }
                    }
                }
            }

            // 3. 写字楼房源
            $officeBuildingHouse = OfficeBuildingHouse::all();
            $officeBuildingTemps = array();
            foreach ($officeBuildingHouse as $k => $v) {
                // 查询最后一条
                $temp = Track::where([
                    'house_model' => 'App\Models\OfficeBuildingHouse',
                    'house_id' => $v->id,
                    'user_id' => $v->guardian
                ])->orderBy('id', 'desc')
                    ->first();

                if (empty($temp)) {
                    $officeBuildingTemps[$k]['id'] = $v->id;
                    // 房源创建时间
                    $officeBuildingTemps[$k]['createTime'] = $v->created_at->toDateString();
                    $officeBuildingTemps[$k]['toPublic'] = true;
                } else {
                    $temp->toPublic = false;
                    $officeBuildingTemps[$k] = $temp;
                }
            }

            foreach ($officeBuildingTemps as $officeBuildingTemp) {
                if ($officeBuildingTemp['toPublic'] == true) {
                    // 判断房源添加时间
                    if (strtotime($officeBuildingTemp['createTime']) + $month < strtotime(date('Ymd'))) {
                        $res = OfficeBuildingHouse::where('id', $officeBuildingTemp['id'])->update(['guardian' => null]);
                        if (!$res) {
                            throw new \Exception('id为:'.$officeBuildingTemp['id'].'的写字楼房源加入公盘失败');
                        }
                    }
                } else {
                    // 判断跟进添加时间
                    if (strtotime($officeBuildingTemp->created_at->toDateString()) + $month < strtotime(date('Ymd'))) {
                        $res = OfficeBuildingHouse::where('id', $officeBuildingTemp->id)->update(['guardian' => null]);
                        if (!$res) {
                            throw new \Exception('id为:'.$officeBuildingTemp->id.'的写字楼房源加入公盘失败');
                        }
                    }
                }
            }

            // 4. 客户
            $customs = Custom::all();
            $customsTemps = array();
            foreach ($customs as $k => $v) {
                // 查询最后一条
                $temp = Track::where([
                    'custom_id' => $v->id,
                    'user_id' => $v->guardian,
                ])->orderBy('id', 'desc')
                    ->first();

                if (empty($temp)) {
                    $customsTemps[$k]['id'] = $v->id;
                    // 房源创建时间
                    $customsTemps[$k]['createTime'] = $v->created_at->toDateString();
                    $customsTemps[$k]['toPublic'] = true;
                } else {
                    $temp->toPublic = false;
                    $customsTemps[$k] = $temp;
                }
            }

            foreach ($customsTemps as $customsTemp) {
                if ($customsTemp['toPublic'] == true) {
                    // 判断房源添加时间
                    if (strtotime($customsTemp['createTime']) + $month < strtotime(date('Ymd'))) {
                        $res = Custom::where('id', $customsTemp['id'])->update(['guardian' => null]);
                        if (!$res) {
                            throw new \Exception('id为:'.$customsTemp['id'].'的客户加入公盘失败');
                        }
                    }
                } else {
                    // 判断跟进添加时间
                    if (strtotime($customsTemp->created_at->toDateString()) + $month < strtotime(date('Ymd'))) {
                        $res = Custom::where('id', $customsTemp->id)->update(['guardian' => null]);
                        if (!$res) {
                            throw new \Exception('id为:'.$customsTemp->id.'的客户加入公盘失败');
                        }
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
