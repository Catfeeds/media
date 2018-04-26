<?php
namespace App\Repositories;

use App\Handler\Common;
use App\Models\OwnerViewRecord;
use App\Models\Track;
use Mockery\Exception;

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
        \DB::beginTransaction();
        try {
            $tracks = $this->model->create([
                'house_id' => $request->house->id,
                'user_id' => Common::user()->id,
                'custom_id' => $request->custom_id,
                'tracks_mode' => $request->tracks_mode,
                'conscientious_id' => $request->conscientious_id,
                'tracks_time' => $request->tracks_time,
                'content' => $request->content,
            ]);
           if (empty($tracks)) {
               throw new Exception('房源跟进信息添加失败');
           }
            $res = OwnerViewRecord::where([
                'user_id' => Common::user()->id,
                'house_id' => $request->house->id
            ])->update(['status' => 2]);
           if (!$res->save()) {
               throw new Exception('房源跟进信息添加失败');
           }
            \DB::commit();
           return true;
        } catch (Exception $e) {
            \Log::error('房源跟进信息添加失败',$e->getMessage());
            return false;
        }
    }

}