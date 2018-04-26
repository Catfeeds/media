<?php
/**
 * Created by PhpStorm.
 * User: jacklin
 * Date: 2018/4/26
 * Time: 下午6:07
 */
trait HouseAttribute {

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