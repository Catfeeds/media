<?php

namespace App\Http\Controllers\API;

use App\Models\Storefront;
use App\Repositories\StorefrontsRepository;
use App\Http\Requests\API\StorefrontsRequest;
use Illuminate\Http\Request;


class StorefrontsController extends APIBaseController
{
    /**
     * 说明 :门店列表
     *
     * @param Request $request
     * @param StorefrontsRepository $storefrontsRepository
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
	public function index
    (
        Request $request,
        StorefrontsRepository $storefrontsRepository

    )
	{
	    $res = $storefrontsRepository->getStorefrontsList($request);
	    return $this->sendResponse($res,'门店列表获取成功');
	}

    /**
     * 说明 : 门店添加
     *
     * @param StorefrontsRequest $request
     * @param StorefrontsRepository $storefrontsRepository
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function store
    (
    	StorefrontsRequest $request,
    	StorefrontsRepository $storefrontsRepository
    )
    {
    	$res = $storefrontsRepository->addStorefronts($request);
        return $this->sendResponse($res, '门店信息添加成功');
    }

    /**
     * 说明:  门店修改之前原始数据
     *
     * @param Storefront $storefront
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
        public function edit(Storefront $storefront)
    {
        return $this->sendResponse($storefront,'门店修改之前原始数据');
    }

    /**
     * 说明:门店修改
     *
     * @param StorefrontsRepository $storefrontsRepository
     * @param StorefrontsRequest $request
     * @param Storefront $storefront
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function update
    (
        StorefrontsRepository $storefrontsRepository,
        StorefrontsRequest $request,
        Storefront $storefront
    )
    {
        $res = $storefrontsRepository->updateStorefronts($storefront, $request);
        return $this->sendResponse($res,'更新成功');
    }

    /**
     * 说明:删除门店
     *
     * @param Storefront $storefront
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @author 刘坤涛
     */
    public function destroy(Storefront $storefront)
    {
        $res = $storefront->delete();
        return $this->sendResponse($res,'删除成功');
    }
}

