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
        switch ($request->house_model) {
            case '1':
                $model = "App\\Models\\DwellingHouse";
                break;
            case '2':
                $model = "App\\Models\\OfficeBuildingHouse";
                break;
            case '3':
                $model = "App\\Models\\ShopsHouse";
                break;
            default;
                break;
        }
        return $this->model->create([
            'user_id' => Common::user()->id,
            'house_id' => $request->house_id,
            'house_model' => $model,
        ]);
    }
}