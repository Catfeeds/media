<?php

namespace App\Repositories;

use App\Models\Custom;
use App\Models\CustomRelArea;
use App\Models\CustomRelBuilding;

class CustomRepository extends BaseRepository
{

    private $model;

    public function __construct(Custom $model)
    {
        $this->model = $model;
    }

    public function add($request)
    {
        \DB::beginTransaction();
        try {
            // 添加客户表
            $res = $this->model->create([
                'status' => $request->status,
                'class' => $request->class,
                'source' => $request->source,
                'belong' => $request->belong,
                'name' => $request->name,
                'tel' => $request->tel,
                'price_low' => $request->price_low,
                'price_high' => $request->price_high,
                'payment_type' => $request->payment_type,
                'pay_commission' => $request->pay_commission,
                'pay_commission_unit' => $request->pay_commission_unit,
                'need_type' => $request->need_type,
                'renting_style' => $request->renting_style,
                'office_building_type' => $request->office_building_type,
                'shops_type' => $request->shops_type,
                'acre_low' => $request->acre_low,
                'acre_high' => $request->acre_high,
                'room' => $request->room,
                'hall' => $request->hall,
                'toilet' => $request->toilet,
                'kitchen' => $request->kitchen,
                'balcony' => $request->balcony,
                'floor_low' => $request->floor_low,
                'floor_high' => $request->floor_high,
                'renovation' => $request->renovation,
                'orientation' => $request->orientation,
                'subway' => $request->subway,
                'walk_to_subway' => $request->walk_to_subway,
                'bus' => $request->bus,
                'walk_to_bus' => $request->walk_to_bus,
                'not_like' => $request->not_like,
                'area_id' => $request->area_id,
                'other' => $request->other
            ]);

            if (!empty($request->buildings)) {
                foreach ($request->buildings as $building) {
                    CustomRelBuilding::create([
                        'custom_id' => $res->id,
                        'building_id' => $building
                    ]);
                }
            }

        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \DB::rollBack();
            return false;
        }
        \DB::commit();
        return $res;
    }

    public function updateData($custom, $request)
    {
        \DB::beginTransaction();
        try {
            // 更新客户信息
            $res = $custom->update([
                'status' => $request->status,
                'class' => $request->class,
                'source' => $request->source,
                'belong' => $request->belong,
                'name' => $request->name,
                'tel' => $request->tel,
                'price_low' => $request->price_low,
                'price_high' => $request->price_high,
                'payment_type' => $request->payment_type,
                'pay_commission' => $request->pay_commission,
                'pay_commission_unit' => $request->pay_commission_unit,
                'need_type' => $request->need_type,
                'renting_style' => $request->renting_style,
                'office_building_type' => $request->office_building_type,
                'shops_type' => $request->shops_type,
                'acre_low' => $request->acre_low,
                'acre_high' => $request->acre_high,
                'room' => $request->room,
                'hall' => $request->hall,
                'toilet' => $request->toilet,
                'kitchen' => $request->kitchen,
                'balcony' => $request->balcony,
                'floor_low' => $request->floor_low,
                'floor_high' => $request->floor_high,
                'renovation' => $request->renovation,
                'orientation' => $request->orientation,
                'subway' => $request->subway,
                'walk_to_subway' => $request->walk_to_subway,
                'bus' => $request->bus,
                'walk_to_bus' => $request->walk_to_bus,
                'not_like' => $request->not_like,
                'area_id' => $request->area_id,
                'other' => $request->other
            ]);

            // 得到新老意向楼盘
            $buildings = $request->buildings;
            $buildingsOld = $custom->buildings->pluck('id')->toArray();

            // 老数组减去新数组 得到应该删除的id
            $delIds = array_diff($buildingsOld, $buildings);
            foreach ($delIds as $delId) {
                CustomRelBuilding::find($delId)->delete();
            }
            // 新数组减去老鼠组 得到应该添加的id
            $addIds = array_diff($buildings, $buildingsOld);
            foreach ($addIds as $addId) {
                CustomRelBuilding::create([
                    'custom_id' => $custom->id,
                    'building_id' => $addId
                ]);
            }
        }
        catch (\Exception $exception) {
            \Log::error($exception);
            \DB::commit();
            return false;
        }
        \DB::commit();
        return $res;
    }
}