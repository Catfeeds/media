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
        return $this->model->create([
            'user_id' => Common::user()->id,
            'house_id' => $request->id,
        ]);
    }
}