<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\TracksRequest;
use App\Repositories\TracksRepository;
use App\Services\TracksService;

class TracksController extends APIBaseController
{
    /**
     * 说明: 添加跟进相关数据获取成功
     *
     * @param TracksService $tracksService
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function create(
        TracksService $tracksService
    )
    {
        $result = $tracksService->relevantData();
        return $this->sendResponse($result, '添加跟进相关数据获取成功');
    }

    /**
     * 说明:获取房源跟进记录表
     *
     * @param TracksRequest $request
     * @param TracksRepository $tracksRepository
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function index
    (
        TracksRequest $request,
        TracksRepository $tracksRepository
    )
    {
       $res= $tracksRepository->tracksList($request);
        return $this->sendResponse($res,'房源跟进列表获取成功');

    }

    public function getCustomTracksList
    (
        TracksRequest $request,
        TracksRepository $tracksRepository
    )
    {
        $res = $tracksRepository->getCustomTracksList($request);
        return $this->sendResponse($res, '客户跟进列表获取成功');
    }

    /**
     * 说明:添加房源跟进信息
     *
     * @param TracksRequest $request
     * @param TracksRepository $tracksRepository
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function store(
        TracksRequest $request,
        TracksRepository $tracksRepository
    )
    {
        $res = $tracksRepository->addTracks($request);
        if ($res) {
          return  $this->sendResponse($res,'房源跟进信息添加成功');
        }
        return $this->sendError('房源跟进信息添加失败');
    }


    public function addCustomsTracks
    (
        TracksRequest $request,
        TracksRepository $tracksRepository
    )
    {
        //验证house_id是否存,如果存在,必须存在house_model
        if (empty($request->house_id) && !empty($request->house_model) || !empty($request->house_id) && empty($request->house_model)) {
            return $this->sendError('房源参数错误');
        }
        $res = $tracksRepository->addCustomsTracks($request);
        if ($res) {
            return $this->sendResponse($res,'客户跟进信息添加成功');
        }
        return $this->sendError('客户跟进信息添加失败');
    }

    /**
     * 说明:获取当前登录用户同事信息
     *
     * @param TracksService $tracksService
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function getColleague(TracksService $tracksService)
    {
        $res = $tracksService->getColleagueInfo()->map(function($v) {
                return [
                    'label' => $v->real_name,
                    'value' => $v->id
                ];
        });
        return $this->sendResponse($res,'同事信息获取成功');
    }
}
