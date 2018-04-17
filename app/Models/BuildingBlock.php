<?php

namespace App\Models;


class BuildingBlock extends BaseModel
{
    protected $appends = ['info'];

    /**
     * 说明：所属楼盘
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author jacklin
     */
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * 说明：获取楼座info
     *
     * @return string
     * @author jacklin'
     */
    public function getInfoAttribute()
    {
        $building = $this->building;

        $blocksInfo = $this->name . $this->name_unit . $this->unit . $this->unit_unit;
        return $building->name . $blocksInfo;
    }
}
