<?php
namespace App\Repositories;

use App\Models\Track;

class TracksRepository extends BaseRepository
{

    private $model;

    public function __construct(Track $model)
    {
        $this->model = $model;
    }

    /**
     * 说明: 房源添加跟进
     *
     * @param $request
     * @return mixed
     * @author 罗振
     */
    public function addTracks($request)
    {
        return $this->model->create([
            'house_id' => $request->house_id??null,
            'user_info' => $request->user_info??null,
            'custom_id' => $request->custom_id,
            'tracks_mode' => $request->tracks_mode,
            'conscientious_id' => $request->conscientious_id,
            'tracks_time' => $request->tracks_time,
            'content' => $request->content
        ]);
    }

}