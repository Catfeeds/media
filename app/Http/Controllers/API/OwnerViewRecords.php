<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\OwnerViewRecordsRequest;
use App\Repositories\OwnerViewRecordsRepository;

class OwnerViewRecords extends APIBaseController
{

    /**
     * 说明:查看房源时添加查看记录
     *
     * @param OwnerViewRecordsRepository $ownerViewRecordsRepository
     * @param OwnerViewRecordsRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function store
    (
        OwnerViewRecordsRepository $ownerViewRecordsRepository,
        OwnerViewRecordsRequest $request
    )
    {
        $res = $ownerViewRecordsRepository->addRecord($request);
        if ($res) {
            return $this->sendResponse($res, '查看房源记录添加成功');
        }
        return $this->sendError('记录添加失败');

    }
}
