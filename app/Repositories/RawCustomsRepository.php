<?php

namespace App\Repositories;

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
                'recorder' => $request->recorder
            ]);
            if (!$custom) throw new \Exception('客户信息录入失败');
            $custom->identifier = $service->setHouseIdentifier('gd',$custom->id);
            if (!$custom->save()) throw new \Exception('客户信息录入失败');
            \DB::commit();
            return $custom;
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('客户信息录入失败'. $e->getMessage());
            return false;
        }

    }

    //工单列表
    public function getList($request, $service)
    {
        $model = $this->model->with('shopkeeperUser','staffUser','custom','house');
        if ($request->tel) $model = $model->where('tel', $request->tel);
        if ($request->demand) $model = $model->where('demand', $request->demand);
        if ($request->time) $model = $model->whereBetween('created_at', $request->time);
        if ($request->source) $model = $model->where('source', $request->source);
        // 获取转换率
        $conversionRate = $service->getConversionRate($model);
        $item =  $model->latest()->paginate($request->per_page);
        $service->getStaffInfo($item);
        return ['page' => $service->getGdInfo($item), 'conversionRate' => $conversionRate];
    }

    //店长分配工单
    public function distribution($request)
    {
        return $this->model->where('id', $request->id)->update(['staff_id'=> $request->staff_id, 'shopkeeper_deal' => time()]);
    }

    //店员确认工单
    public function determine($request)
    {
        return $this->model->where('id', $request->id)->update(['staff_deal' => time()]);
    }

    //手机端店长处理工单界面
    public function shopkeeperList($request, $service, $id)
    {
        switch ($request->status) {
            //待处理页面
            case 1 :
                return $this->model->where(['shopkeeper_id' => $id, 'shopkeeper_deal' => null])->latest()->paginate(6);
                break;
            //已处理
            case 2:
                $item = $this->model->with('staffUser')->where('shopkeeper_id', $id)->where('shopkeeper_deal','!=', null)->latest()->paginate(6);
                return $service->getInfo($item);
                break;
        }
    }

    //业务员处理页面
    public function staffList($request, $service, $id)
    {
        switch ($request->status) {
            case 1:
                return $this->model->where(['staff_deal' => null, 'staff_id' => $id])->latest()->paginate(6);
                break;
            case 2:
                $item = $this->model->with('custom')->where('staff_deal', '!=', null)->where('staff_id', $id)->latest()->paginate(6);
                return $service->getStaffInfo($item);
                break;
        }
    }
}