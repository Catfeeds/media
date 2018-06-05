<?php

namespace App\Http\Controllers\API;

use App\Handler\Common;
use App\Models\Storefront;
use App\Repositories\StorefrontsRepository;
use App\Http\Requests\API\StorefrontsRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class StorefrontsController extends APIBaseController
{
    /**
     * 说明: 门店列表
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
//	    if (empty(Common::user()->can('storefronts_list'))) {
//	        return $this->sendError('无门店列表权限','403');
//        }

        // 获取当前权限等级
        $user = Common::user();

        switch ($user->level) {
            case 1: // 总经理 查看所有门店
                $where = [];
                break;
            case 2: // 区域经理查看属于自己的门店
                $where = ['area_manager_id' => $user->id];
                break;
        }
	    $res = $storefrontsRepository->getStorefrontsList($where, $request);
	    return $this->sendResponse($res,'门店列表获取成功');
	}

    /**
     * 说明: 获取区域经理
     *
     * @param UserRepository $userRepository
     * @return mixed
     * @author 罗振
     */
    public function create(
        UserRepository $userRepository
    )
    {
        $user = Common::user();
        switch ($user->level) {
            case 1:
                $where = [];
                break;
            case 2:
                $where = ['id' => $user->id];
        }

        $result= $userRepository->getAllAreaManager($where)->map(function($v) {
            return [
                    'label' => $v->real_name,
                    'value' => $v->id
                    ];
        });
        return $this->sendResponse($result,'区域经理信息获取成功');
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
        if (empty(Common::user()->can('add_storefronts'))) {
            return $this->sendError('无添加门店权限','403');
        }

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
        if (empty(Common::user()->can('update_storefronts'))) {
            return $this->sendError('无更新门店权限','403');
        }

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
        if (empty(Common::user()->can('del_storefronts'))) {
            return $this->sendError('无删除门店权限','403');
        }

        $res = $storefront->delete();
        return $this->sendResponse($res,'删除成功');
    }


    /**
     * 说明: 获取所有门店信息
     *
     * @param StorefrontsRepository $storefrontsRepository
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function getAllStorefrontsInfo(
        StorefrontsRepository $storefrontsRepository
    )
    {
        $result = $storefrontsRepository->getAllStorefrontsInfo()->map(function($v) {
            return [
                'label' => $v->storefront_name,
                'value' => $v->id
            ];
        });
        return $this->sendResponse($result,'获取所有门店信息成功');
    }

}

