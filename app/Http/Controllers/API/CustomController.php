<?php

namespace App\Http\Controllers\API;

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
     * @param Request $request
     * @param CustomRepository $repository
     * @return \Illuminate\Http\JsonResponse
     * @author jacklin
     */
    public function store(Request $request, CustomRepository $repository)
    {
        $res = $repository->add($request);
        return $this->sendResponse($res, '添加成功');
    }
}
