<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\GroupAssociationsRequest;
use App\Models\GroupAssociation;
use App\Repositories\GroupAssociationsRepository;
use Illuminate\Http\Request;

class GroupAssociationsController extends APIBaseController
{
    /**
     * 说明: 组列表
     *
     * @param Request $request
     * @param GroupAssociationsRepository $repository
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function index(
        Request $request,
        GroupAssociationsRepository $repository
    )
    {
        $res = $repository->groupAssociationsList($request->per_page??10);
        return $this->sendResponse($res,'获取组列表成功');
    }

    /**
     * 说明: 添加组
     *
     * @param GroupAssociationsRequest $request
     * @param GroupAssociationsRepository $repository
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function store(
        GroupAssociationsRequest $request,
        GroupAssociationsRepository $repository
    )
    {
        $res = $repository->addGroupAssociations($request);
        return $this->sendResponse($res,'添加组成功');
    }

    /**
     * 说明: 获取组原始数据
     *
     * @param GroupAssociation $groupAssociation
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function edit(
        GroupAssociation $groupAssociation
    )
    {
        return $this->sendResponse($groupAssociation, '获取组原始数据');
    }

    /**
     * 说明: 修改组
     *
     * @param GroupAssociationsRequest $request
     * @param GroupAssociation $groupAssociation
     * @param GroupAssociationsRepository $repository
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function update(
        GroupAssociationsRequest $request,
        GroupAssociation $groupAssociation,
        GroupAssociationsRepository $repository
    )
    {
        $res = $repository->updateGroupAssociation($request, $groupAssociation);
        if (empty($res)) return $this->sendError('组信息修改失败');
        return $this->sendResponse($res,'组信息修改成功');
    }
}

