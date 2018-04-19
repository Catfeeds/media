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

    public function addStroefronts($request)
    {
    	return $this->model->create([
    		   'storefront_name' => $request->storefront_name,
    		   'address' => $request->address,
    		    'user_id' => $request->user_id,
    		    'fixed_tel' => $request->fixed_tel 
    		]);
    }

    public function getStorefrontsList($request)
    {
        return $this->model->paginate(10);
    }

    public function updateStorefronts($storefront, $request)
    {
        $storefront->storefront_name = $request->storefront_name;
        $storefront->address = $request->address;
        $storefront->user_id = $request->user_id;
        $storefront->fixed_tel = $request->fixed_tel;

        if (!$storefront->save()) {
            return false;
        }

        return true;

    }
}
