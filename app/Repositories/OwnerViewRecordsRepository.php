<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\OwnerViewRecord;

class OwnerViewRecordsRepository extends BaseRepository
{

    private $model;

    public function __construct(OwnerViewRecord $model)
    {
        $this->model = $model;
    }

    /**
     * 说明:查看房源时添加查看记录
     *
     * @param $request
     * @return mixed
     * @author 刘坤涛
     */
    public function addRecords($request)
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
        return $this->model->create([
            'user_id' => Common::user()->id,
            'house_id' => $request->house_id,
            'house_model' => $model,
        ]);
    }
}