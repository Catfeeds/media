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
     * 说明:获取房源跟进记录表
     *
     * @param $request
     * @return mixed
     * @author 刘坤涛
     */
    public function tracksList($request)
    {
        return Track::where('house_id', $request->house_id)->paginate($request->per_page);
    }

    /**
     * 说明:获取客户跟进表
     *
     * @param $request
     * @return mixed
     * @author 刘坤涛
     */
    public function getCustomsTracksList($request)
    {
        $res =  Track::where('custom_id', $request->custom_id)->paginate($request->per_page);
        foreach ($res as $v) {
            if ($v->house_id) {
                $item = $v['house_model']::where('id', $v['house_id'])->first();
                $v->house_number_all = $item->building_name . $item->house_number_info;
            }
        }
        return $res;
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
       switch ($request->house_model) {
           case '1':
               $model = "App\\Models\\DwellingHouse";
               break;
           case '2':
               $model = "App\\Models\\DwellingHouse";
               break;
           case '3':
               $model = "App\\Models\\DwellingHouse";
               break;
           default;
               break;
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
                'status' => 1
            ])->first();
            if ($OwnerViewRecord) {
                $res = $OwnerViewRecord->update(['status' => 2]);
                if (!$res) throw new \Exception('查看房源记录更新失败');
            }
            \DB::commit();
           return true;
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('房源跟进信息添加失败'. $e->getMessage());
            return false;
        }
    }

    /**
     * 说明:添加客户跟进信息
     *
     * @param $request
     * @return mixed
     * @author 刘坤涛
     */
    public function addCustomsTracks($request)
    {
            switch ($request->house_model) {
                case '1':
                    $model = "App\\Models\\DwellingHouse";
                    break;
                case '2':
                    $model = "App\\Models\\DwellingHouse";
                    break;
                case '3':
                    $model = "App\\Models\\DwellingHouse";
                    break;
                default;
                    $model = null;
                    break;
        }
        return $this->model->create([
            'house_model' => $model,
            'house_id' => $request->house_id,
            'user_id' => Common::user()->id,
            'custom_id' => $request->custom_id,
            'tracks_mode' => $request->tracks_mode,
            'content' => $request->content,
        ]);
    }

}