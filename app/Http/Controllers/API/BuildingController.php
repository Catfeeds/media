<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\BuildingRequest;
use App\Repositories\BuildingRepository;

class BuildingController extends APIBaseController
{

    public function index(BuildingRequest $request, BuildingRepository $repository)
    {

    }
    public function store(BuildingRequest $request, BuildingRepository $repository)
    {
        $res = $repository->add($request);
        if (empty($request->building_block)) return $this->sendError(405, '楼栋信息不能为空');
        return $this->sendResponse($res, 200);
    }
}
