<?php
namespace App\Repositories;

use App\Handler\Common;
use App\Models\OwnerViewRecord;
use App\Models\Track;

class TracksRepository extends BaseRepository
{

    private $model;

    public function __construct(Track $model)
    {
        $this->model = $model;
    }

    /**
     * 说明:添加房源跟进信息并修改查看房源记录状态
     *
     * @param $request
     * @return bool
     * @author 刘坤涛
     */
    public function addTracks($request)
    {
       if ($request->house_model == 1) {
            $model = "App\\Models\\DwellingHouse";
       } elseif ($request->house_model == 2) {
           $model = "App\\Models\\OfficeBuildingHouse";
       } elseif ($request->house_model == 3) {
           $model = "App\\Models\\ShopsHouse";
       } else {
           $model = '';
       }
        \DB::beginTransaction();
        try {
            $tracks = $this->model->create([
                'house_model' => $model,
                'house_id' => $request->house_id,
                'user_id' => Common::user()->id,
                'custom_id' => $request->custom_id,
                'tracks_mode' => $request->tracks_mode,
                'content' => $request->content,
            ]);
            if (empty($tracks)) {
               throw new \Exception('房源跟进信息添加失败');
           }
            $OwnerViewRecord = OwnerViewRecord::where([
                'user_id' => Common::user()->id,
                'house_id' => $request->house_id,
            ])->first();
            if ($OwnerViewRecord) {
                $res = $OwnerViewRecord->update(['status' => 2]);
                if (!$res) {
                    throw new \Exception('查看房源记录更新失败');
                }
            }

            \DB::commit();
           return true;
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('房源跟进信息添加失败'. $e->getMessage());
            return false;
        }
    }

}