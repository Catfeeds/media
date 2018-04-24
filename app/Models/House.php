<?php

namespace App\Models;

class House extends BaseModel
{

    protected $appends = ['house_number_info', 'address'];

    /**
     * 说明：房号
     *
     * @return string
     * @author jacklin
     */
    public function getHouseNumberInfoAttribute()
    {
        $block = $this->buildingBlock->block_info;
        if (!empty($this->house_number)) $block .= $this->house_number . '室';
        return $block;
    }

    /**
     * 说明：地址
     *
     * @return mixed
     * @author jacklin
     */
    public function getAddressAttribute()
    {
        return $this->buildingBlock->building->address;
    }
}
