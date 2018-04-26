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
            return $this->sendResponse($res, '查看房源记录添加成功');
        }
        return $this->sendError('记录添加失败');

    }
}
