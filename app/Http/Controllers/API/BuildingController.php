<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\BuildingRequest;
use App\Models\Building;
use App\Repositories\BuildingRepository;

class BuildingController extends APIBaseController
{

    public function index(BuildingRequest $request, BuildingRepository $repository)
    {

    }

    /**
     * 说明：楼盘添加
     *
     * @param BuildingRequest $request
     * @param BuildingRepository $repository
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function store(BuildingRequest $request, BuildingRepository $repository)
    {
        // 楼栋信息不能为空
        if (empty($request->building_block)) return $this->sendError(405, '楼栋信息不能为空');
        // 楼盘名不允许重复
        $res = Building::where(['name' => $request->name, 'street_id' => $request->street_id])->first();
        if (!empty($res)) return $this->sendError(405, '楼盘名不能重复');
        $res = $repository->add($request);

        return $this->sendResponse($res, 200);
    }


    public function buildingSelect()
    {
        
    }
}
