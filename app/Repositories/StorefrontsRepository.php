<?php
namespace App\Repositories;

use App\Models\Storefront;
use Illuminate\Support\Facades\Auth;

class StorefrontsRepository extends BaseRepository
{
    private $model;

    public function __construct(Storefront $model)
    {
        $this->model = $model;
    }

    /**
     * 说明: 门店添加
     *
     * @param $request
     * @return mixed
     * @author 刘坤涛
     */
    public function addStorefronts($request)
    {
    	return $this->model->create([
            'storefront_name' => $request->storefront_name,
            'address' => $request->address,
            'fixed_tel' => $request->fixed_tel,
            'area_manager_id' => Auth::guard('api')->user()->id
    	]);
    }

    /**
     * 说明: 门店列表
     *
     * @param $request
     * @return mixed
     * @author 刘坤涛
     */
    public function getStorefrontsList($request)
    {
        return $this->model->paginate(10);
    }

    /**
     * 说明: 门店修改
     *
     * @param $storefront
     * @param $request
     * @return bool
     * @author 刘坤涛
     */
    public function updateStorefronts($storefront, $request)
    {
        $storefront->storefront_name = $request->storefront_name;
        $storefront->address = $request->address;
        $storefront->fixed_tel = $request->fixed_tel;

        if (!$storefront->save()) {
            return false;
        }

        return true;
    }
}
