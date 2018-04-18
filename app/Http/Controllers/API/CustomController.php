<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CustomRequest;
use App\Models\Custom;
use App\Repositories\CustomRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomController extends APIBaseController
{
    public function index()
    {
        
    }

    /**
     * 说明：添加客户状态
     *
     * @param CustomRequest $request
     * @param CustomRepository $repository
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function store(CustomRequest $request, CustomRepository $repository)
    {
        $res = $repository->add($request);
        return $this->sendResponse($res, '添加成功');
    }

    /**
     * 说明：更新客户信息
     *
     * @param CustomRequest $request
     * @param Custom $custom
     * @param CustomRepository $repository
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function update
    (
        CustomRequest $request,
        Custom $custom,
        CustomRepository $repository
    )
    {
        $res = $repository->updateData($custom, $request);
        if (!$res) return $this->sendError('更新失败');
        return $this->sendResponse($res, '更新成功');
    }
}
