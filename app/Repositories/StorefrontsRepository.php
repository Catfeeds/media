<?php
namespace App\Repositories;

use App\Models\Storefront;

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
            'area_manager_id' => $request->area_manager_id
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
        return $this->model->paginate($request->per_page);
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
        $storefront->area_manager_id = $request->area_manager_id;

        if (!$storefront->save()) {
            return false;
        }

        return true;
    }

    /**
     * 说明: 获取所有门店信息
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     * @author 罗振
     */
    public function getAllStorefrontsInfo()
    {
        return $this->model->all();
    }
}
