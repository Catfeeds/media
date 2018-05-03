<?php

namespace App\Models;

use App\Traits\HouseTraits;

class ShopsHouse extends BaseModel
{
    use HouseTraits;

    protected $casts = [
        'owner_info' => 'array',
        'support_facilities' => 'array',
        'fit_management' => 'array',
        'cost_detail' => 'array',
        'house_type_img' => 'array',
        'indoor_img' => 'array',
        'check_in_time' => 'date',
        'min_acreage' => 'float',
        'constru_acreage' => 'float',
        'wide' => 'float',
        'depth' => 'float',
        'storey' => 'float',
        'rent_price' => 'float',
        'increasing_situation' => 'float',
        'transfer_fee' => 'float',
        'pay_commission' => 'float',
        'storefront' => 'array'
    ];

    protected $appends = [
        'renovation_cn', 'shops_type_cn',
        'pay_commission_cn', 'payment_type_cn', 'shortest_lease_cn', 'rent_free_cn',
        'frontage_cn', 'split_cn', 'orientation_cn', 'prospecting_cn', 'see_house_time_cn',
        'house_proxy_type_cn', 'source_cn', 'certificate_type_cn', 'house_type_img_cn',
        'indoor_img_cn', 'building_name', 'house_busine_state_cn', 'house_number_info',
        'address', 'check_in_time_cn', 'constru_acreage_cn', 'rent_price_cn',
        'increasing_situation_cn', 'min_acreage_cn', 'wide_cn', 'depth_cn',
        'transfer_fee_cn', 'guardian_cn', 'storefronts_cn',
        'tracks_time','house_img_cn','disc_type_cn','see_power_cn'
    ];

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
            $houseType = $this->room.'室';
        }
        if (!empty($this->hall)) {
            $houseType = $houseType.$this->hall.'厅';
        }

        return $houseType;
    }

    /**
     * 说明: 商铺类型
     *
     * @return string
     * @use shops_type_cn
     * @author 罗振
     */
    public function getShopsTypeCnAttribute()
    {
        if ($this->shops_type == 1) {
            return '住宅底商';
        } elseif ($this->shops_type == 2) {
            return '商业街商铺';
        } elseif ($this->shops_type == 3) {
            return '酒店商底';
        } elseif ($this->shops_type == 4) {
            return '社区商铺';
        } elseif ($this->shops_type == 5) {
            return '沿街商铺';
        } elseif ($this->shops_type == 6) {
            return '写字底商';
        } elseif ($this->shops_type == 7) {
            return '购物中心';
        } elseif ($this->shops_type == 8) {
            return '旅游商铺';
        } elseif ($this->shops_type == 9) {
            return '其他';
        } else {
            return '';
        }
    }

    /**
     * 说明: 是否临街中文
     *
     * @return string
     * @use frontage_cn
     * @author 罗振
     */
    public function getFrontageCnAttribute()
    {
        if ($this->frontage == 1) {
            return '临街';
        } elseif ($this->frontage == 2) {
            return '不临街';
        } else {
            return '';
        }
    }

    /**
     * 说明: 是否可拆分
     *
     * @return string
     * @use split_cn
     * @author 罗振
     */
    public function getSplitCnAttribute()
    {
        if ($this->split == 1) {
            return '可拆分';
        } elseif ($this->split == 2) {
            return '不可拆分';
        } else {
            return '';
        }
    }

    /**
     * 说明: 免租期中文
     *
     * @return string
     * @use rent_free_cn
     * @author 罗振
     */
    public function getRentFreeCnAttribute()
    {
        if ($this->rent_free == 1) {
            return '1个月';
        } elseif ($this->rent_free == 2) {
            return '2个月';
        } elseif ($this->rent_free == 3) {
            return '3个月';
        } elseif ($this->rent_free == 4) {
            return '4个月';
        } elseif ($this->rent_free == 5) {
            return '5个月';
        } elseif ($this->rent_free == 6) {
            return '6个月';
        } elseif ($this->rent_free == 7) {
            return '7个月';
        } elseif ($this->rent_free == 8) {
            return '8个月';
        } elseif ($this->rent_free == 9) {
            return '9个月';
        } elseif ($this->rent_free == 10) {
            return '10个月';
        } elseif ($this->rent_free == 11) {
            return '面谈';
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
            return '1-2年';
        } elseif ($this->shortest_lease == 2) {
            return '2-3年';
        } elseif ($this->shortest_lease == 3) {
            return '3-4年';
        } elseif ($this->shortest_lease == 4) {
            return '5年以上';
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
            if ($this->rent_price_unit == 1) {
                return $this->rent_price.'元/月';
            } else {
                return $this->rent_price.'元/m².月';
            }
        }
    }

    /**
     * 说明: 递增情况
     *
     * @return string
     * @use increasing_situation_cn
     * @author 罗振
     */
    public function getIncreasingSituationCnAttribute()
    {
        if (empty($this->increasing_situation)) {
            return '';
        } else {
            return $this->increasing_situation.'%';
        }
    }

    /**
     * 说明: 最小面积
     *
     * @return string
     * @use min_acreage_cn
     * @author 罗振
     */
    public function getMinAcreageCnAttribute()
    {
        if (empty($this->min_acreage)) {
            return '';
        } else {
            return $this->min_acreage.'㎡';
        }
    }

    /**
     * 说明: 面宽
     *
     * @return string
     * @use wide_cn
     * @author 罗振
     */
    public function getWideCnAttribute()
    {
        if (empty($this->wide)) {
            return '';
        } else {
            return $this->wide.'㎡';
        }
    }

    /**
     * 说明: 进深
     *
     * @return string
     * @use depth_cn
     * @author 罗振
     */
    public function getDepthCnAttribute()
    {
        if (empty($this->depth)) {
            return '';
        } else {
            return $this->depth.'㎡';
        }
    }

    /**
     * 说明: 转让费
     *
     * @return string
     * @use transfer_fee_cn
     * @author 罗振
     */
    public function getTransferFeeCnAttribute()
    {
        if (empty($this->transfer_fee)) {
            return '';
        } else {
            return $this->transfer_fee.'元';
        }
    }

}
