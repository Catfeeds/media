<?php

namespace App\Http\Controllers\API;


use App\Http\Requests\API\OwnerViewRecordsRequest;
use App\Repositories\OwnerViewRecordsRepository;

class OwnerViewRecordsController extends APIBaseController
{


    public function store
    (
        OwnerViewRecordsRepository $ownerViewRecordsRepository,
        OwnerViewRecordsRequest $request
    )
    {
        $res = $ownerViewRecordsRepository->addRecords($request);
        if ($res) {
            return $this->sendResponse($res, '房源查看记录添加成功');
        }
        return $this->sendError('房源查看记录添加失败');
    }
}
