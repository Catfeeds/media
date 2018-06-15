<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\RawCustom;

class RawCustomsRepository extends BaseRepository
{

    private $model;

    public function __construct(RawCustom $model)
    {
        $this->model = $model;
    }

    public function addRawCustom($request, $service)
    {
        \DB::beginTransaction();
        try {
            $custom = $this->model->create([
                'name' => $request->name,
                'tel' => $request->tel,
                'source'=> $request->source,
                'demand' => $request->demand,
                'position' => $request->position,
                'acreage' => $request->acreage,
                'price' => $request->price,
                'shopkeeper_id' => $request->shopkeeper_id,
                'remark' => $request->remark,
                'user_id' => Common::user()->id
            ]);
            if (!$custom) throw new \Exception('客户信息录入失败');
            $custom->identifier = $service->setHouseIdentifier('gd',$custom->id);
            if (!$custom->save()) throw new \Exception('客户信息录入失败');
            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('客户信息录入失败'. $e->getMessage());
            return false;
        }

    }

    public function getList($request, $service)
    {
        $model = $this->model->where('user_id', Common::user()->id)->with('shopkeeperUser','staffUser');
        if ($request->tel) $model = $model->where('tel', $request->tel);
        if ($request->demand) $model = $model->where('demand', $request->demand);
        if ($request->time) $model = $model->whereBetween('created_at', $request->time);
        $item =  $model->latest()->paginate($request->per_page);
        return  $service->getGdInfo($item);
    }
}