<?php

namespace App\Models;

use App\Traits\HouseTraits;

class DwellingHouse extends BaseModel
{
    use HouseTraits;

    protected $casts = [
        'owner_info' => 'array',
        'feature_lable' => 'array',
        'support_facilities' => 'array',
        'cost_detail' => 'array',
        'house_type_img' => 'array',
        'indoor_img' => 'array',
        'check_in_time' => 'date',
        'rent_price' => 'float',
        'constru_a`ge' => 'float',
        'pay_commission' => 'float',
        'storefront' => 'array'
    ];

    protected $appends = [
        'renovation_cn', 'house_type', 'renting_style_cn',
        'house_busine_state_cn', 'payment_type_cn', 'orientation_cn', 'prospecting_cn',
        'see_house_time_cn', 'house_proxy_type_cn', 'source_cn', 'certificate_type_cn',
        'pay_commission_cn', 'shortest_lease_cn', 'house_type_img_cn', 'indoor_img_cn',
        'building_name', 'house_number_info', 'address', 'guardian_cn', 'storefronts_cn',
        'tracks_time', 'constru_acreage_cn', 'rent_price_cn', 'check_in_time_cn',
        'house_img_cn', 'disc_type_cn','see_power_cn'];

    protected $hidden = ['owner_info'];

    /**
     * 说明: 楼座
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author 罗振
     */
    public function buildingBlock()
    {
        return $this->belongsTo('App\Models\BuildingBlock');
    }

    /**
     * 说明: 户型拼接
     *
     * @return string
     * @use house_type
     * @author 罗振
     */
    public function getHouseTypeAttribute()
    {
        $houseType = '';
        if (!empty($this->room)) {
            $houseType = $this->room . '室';
        }
        if (!empty($this->hall)) {
            $houseType = $houseType . $this->hall . '厅';
        }

        if (!empty($this->toilet)) {
            $houseType = $houseType . $this->toilet . '卫';
        }

        if (!empty($this->kitchen)) {
            $houseType = $houseType . $this->kitchen . '厨';
        }

        if (!empty($this->balcony)) {
            $houseType = $houseType . $this->balcony . '阳台';
        }

        return $houseType;
    }

    /**
     * 说明: 出租方式中文
     *
     * @return string
     * @use renting_style_cn
     * @author 罗振
     */
    public function getRentingStyleCnAttribute()
    {
        if ($this->renting_style == 1) {
            return '整租';
        } elseif ($this->renting_style == 2) {
            return '合租';
        } else {
            return '';
        }
    }

    /**
     * 说明: 最短租期中文
     *
     * @return string
     * @use shortest_lease_cn
     * @author 罗振
     */
    public function getShortestLeaseCnAttribute()
    {
        if ($this->shortest_lease == 1) {
            return '1个月';
        } elseif ($this->shortest_lease == 2) {
            return '2个月';
        } elseif ($this->shortest_lease == 3) {
            return '3个月';
        } elseif ($this->shortest_lease == 4) {
            return '4个月';
        } elseif ($this->shortest_lease == 5) {
            return '5个月';
        } elseif ($this->shortest_lease == 6) {
            return '6个月';
        } elseif ($this->shortest_lease == 7) {
            return '7个月';
        } elseif ($this->shortest_lease == 8) {
            return '8个月';
        } elseif ($this->shortest_lease == 9) {
            return '9个月';
        } elseif ($this->shortest_lease == 10) {
            return '10个月';
        } elseif ($this->shortest_lease == 11) {
            return '11个月';
        } elseif ($this->shortest_lease == 12) {
            return '12个月';
        } else {
            return '';
        }
    }

    /**
     * 说明: 户型图拼接url
     *
     * @return static
     * @use house_type_img_cn
     * @author 罗振
     */
    public function getHouseTypeImgCnAttribute()
    {
        return collect($this->house_type_img)->map(function ($img) {
            return [
                'name' => $img,
                'url' => config('setting.qiniu_url') . $img . config('setting.static')
            ];
        })->values();
    }

    /**
     * 说明: 室内图拼接url
     *
     * @return static
     * @use indoor_img_cn
     * @author 罗振
     */
    public function getIndoorImgCnAttribute()
    {
        return collect($this->indoor_img)->map(function ($img) {
            return [
                'name' => $img,
                'url' => config('setting.qiniu_url') . $img . config('setting.static')
            ];
        })->values();
    }

    /**
     * 说明: 租金
     *
     * @return string
     * @use rent_price_cn
     * @author 罗振
     */
    public function getRentPriceCnAttribute()
    {
        if (empty($this->rent_price)) {
            return '';
        } else {
            return $this->rent_price.'元/月';
        }
    }
}
