<?php

namespace App\Http\Controllers\API;


use App\Http\Requests\API\TracksRequest;
use App\Repositories\TracksRepository;

class TracksController extends APIBaseController
{


    /**
     * 说明: 房源添加跟进
     *
     * @param TracksRequest $request
     * @param TracksRepository $tracksRepository
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function store(
        TracksRequest $request,
        TracksRepository $tracksRepository
    )
    {
        $result = $tracksRepository->addTracks($request);
        return $this->sendResponse($result,'房源添加跟进成功');
    }
}
