<?php

namespace App\Models;

class Building extends BaseModel
{
    protected $casts = [
        'company' => 'array',
        'gps' => 'array',
        'album' => 'array',
        'years' => 'string'
    ];

    protected $appends = [
        'type_label', 'area_label', 'block_label', 'blocks_count', 'city_id', 'city_label'
    ];

    /**
     * 说明：楼盘下的所有楼座
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author jacklin
     */
    public function buildingBlocks()
    {
        return $this->hasMany('App\Models\BuildingBlock');
    }

    /**
     * 说明：所属商圈
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author jacklin
     */
    public function block()
    {
        return $this->belongsTo('App\Models\Block');
    }

    /**
     * 说明：所属街道
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author jacklin
     */
    public function area()
    {
        return $this->belongsTo('App\Models\Area');
    }

    /**
     * 说明：楼盘类型信息
     *
     * @return string
     * @author jacklin
     */
    public function getTypeLabelAttribute()
    {
        switch ($this->type) {
            case 1:
                return '住宅';
            case 2:
                return '写字楼';
            case 3:
                return '商铺';
            case 4:
                return '商住两用';
        }
    }

    /**
     * 说明：区域信息
     *
     * @return string
     * @author jacklin
     */
    public function getAreaLabelAttribute()
    {
        $area = $this->area;
        if (empty($area)) return;
        return $area->name;
    }

    /**
     * 说明：商圈信息
     *
     * @return mixed
     * @author jacklin
     */
    public function getBlockLabelAttribute()
    {
        $block = $this->block;
        if (!empty($block)) return $block->name;
    }

    /**
     * 说明：楼座总数
     *
     * @return int
     * @author jacklin
     */
    public function getBlocksCountAttribute()
    {
        return $this->buildingBlocks()->count();
    }

    /**
     * 说明：城市id
     *
     * @return mixed
     * @author jacklin
     */
    public function getCityIdAttribute()
    {
        if (empty($this->street)) return;
        return $this->street->area->city->id;
    }

    /**
     * 说明：城市信息
     *
     * @return mixed
     * @author jacklin
     */
    public function getCityLabelAttribute()
    {
        if (empty($this->area)) return;

        return $this->area->city->name;
    }
}
