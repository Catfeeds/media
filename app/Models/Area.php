<?php

namespace App\Models;

class Area extends BaseModel
{
    /**
     * 说明：所属城市
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author jacklin
     */
    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    /**
     * 说明: 街道下所属楼盘
     *
     * @return mixed
     * @author 罗振
     */
    public function getBuildingAttribute()
    {
        return $this->street->map(function ($item) {
            return Building::where('street_id', $item->id)->get();
        });
    }

    /**
     * 说明: 楼盘下所属楼座
     *
     * @return mixed
     * @author 罗振
     */
    public function getBuildingBlockAttribute()
    {
        return $this->building->flatten()->map(function ($val) {
            if (!empty($val->count())) {
                return BuildingBlock::where('building_id', $val->id)->get();
            }
        });
    }

}
