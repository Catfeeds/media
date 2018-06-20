<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\RawCustom;

class RawCustomsRepository extends BaseRepository
{

    private $model;

    private $user;

    public function __construct(RawCustom $model)
    {
        $this->model = $model;
//        $this->user = Common::user();
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
                'user_id' => $this->user->id
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

    //工单列表
    public function getList($request, $service)
    {
        $model = $this->model->with('shopkeeperUser','staffUser')->where('user_id', 3);
        if ($request->tel) $model = $model->where('tel', $request->tel);
        if ($request->demand) $model = $model->where('demand', $request->demand);
        if ($request->time) $model = $model->whereBetween('created_at', $request->time);
        $item =  $model->latest()->paginate($request->per_page);
        return  $service->getGdInfo($item);
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
    public function shopkeeperList($request, $service)
    {
        switch ($request->status) {
            //待处理页面
            case 1 :
                return $this->model->where(['shopkeeper_id' => 3, 'shopkeeper_deal' => null])->get();
                break;
            //已处理
            case 2:
                $item = $this->model->with('staffUser')->where('shopkeeper_id', 3)->where('shopkeeper_deal','!=', null)->get();
                return $service->getInfo($item);
                break;
        }
    }

    //业务员处理页面
    public function staffList($request, $service)
    {
        switch ($request->status) {
            case 1:
                return $this->model->where(['staff_deal' => null, 'staff_id' => 3])->get();
                break;
            case 2:
                $item = $this->model->with('custom')->where('staff_deal', '!=', null)->where('staff_id', 3)->get();
                return $service->getStaffInfo($item);
                break;
        }
    }
}