<?php

namespace App\Models;


class BuildingBlock extends BaseModel
{
    protected $appends = ['info', 'block_info'];

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

    public function office()
    {
        return $this->hasMany(OfficeBuildingHouse::class);
    }

    public function shop()
    {
        return $this->hasMany(ShopsHouse::class);
    }

    public function dWelling()
    {
        return $this->hasMany(DwellingHouse::class);
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
        if (empty($building)) return;
        $blocksInfo = $this->name . $this->name_unit;
        if (!empty($this->unit)) $blocksInfo = $blocksInfo . $this->unit . $this->unit_unit;
        return $building->name . $blocksInfo;
    }

    /**
     * 说明：获取楼座info
     *
     * @return string
     * @author jacklin'
     */
    public function getBlockInfoAttribute()
    {
        $blocksInfo = $this->name . $this->name_unit;
        if (!empty($this->unit)) $blocksInfo = $blocksInfo . $this->unit . $this->unit_unit;
        return $blocksInfo;
    }
}
