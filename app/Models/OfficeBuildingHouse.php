<?php

namespace App\Models;

use App\Traits\HouseTraits;

class OfficeBuildingHouse extends BaseModel
{
    use HouseTraits;
    protected $casts = [
        'owner_info' => 'array',
        'support_facilities' => 'array',
        'cost_detail' => 'array',
        'house_type_img' => 'array',
        'indoor_img' => 'array',
        'check_in_time' => 'date',
        'constru_acreage' => 'float',
        'min_acreage' => 'float',
        'rent_price' => 'float',
        'increasing_situation' => 'float',
        'pay_commission' => 'float',
        'storefront' => 'array'
    ];

    protected $appends = [
        'renovation_cn', 'house_type', 'office_building_type_cn',
        'house_busine_state_cn', 'payment_type_cn', 'split_cn', 'orientation_cn', 'prospecting_cn',
        'see_house_time_cn', 'house_proxy_type_cn', 'source_cn', 'certificate_type_cn',
        'rent_price_unit_cn', 'pay_commission_cn', 'shortest_lease_cn', 'rent_free_cn',
        'house_type_img_cn', 'indoor_img_cn', 'building_name', 'register_company_cn',
        'open_bill_cn', 'house_number_info', 'address', 'check_in_time_cn',
        'constru_acreage_cn', 'rent_price_cn', 'increasing_situation_cn',
        'min_acreage_cn', 'guardian_cn', 'storefronts_cn',
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
     * 说明: 写字楼类型中文
     *
     * @return string
     * @use office_building_type_cn
     * @author 罗振
     */
    public function getOfficeBuildingTypeCnAttribute()
    {
        if ($this->office_building_type == 1) {
            return '纯写字楼';
        } elseif ($this->office_building_type == 2) {
            return '商住楼';
        } elseif ($this->office_building_type == 3) {
            return '商业综合体楼';
        } elseif ($this->office_building_type == 4) {
            return '酒店写字楼';
        } elseif ($this->office_building_type == 5) {
            return '其他';
        } else {
            return '';
        }
    }

    /**
     * 说明: 公私盘中文
     *
     * @return string
     * @use public_private_cn
     * @author 罗振
     */
    public function getPublicPrivateCnAttribute()
    {
        if ($this->public_private == 1) {
            return '店间公盘';
        } elseif ($this->public_private == 2) {
            return '店内公盘';
        } elseif ($this->public_private == 3) {
            return '私盘';
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
     * 说明: 租金单位转换
     *
     * @return string
     * @use rent_price_unit_cn
     * @author 罗振
     */
    public function getRentPriceUnitCnAttribute()
    {
        if ($this->rent_price_unit == 1) {
            return '%';
        } elseif ($this->rent_price_unit == 2) {
            return '元';
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
     * 说明: 注册公司中文
     *
     * @return string
     * @use register_company_cn
     * @author 罗振
     */
    public function getRegisterCompanyCnAttribute()
    {
        if ($this->register_company == 1) {
            return '可注册';
        } elseif ($this->register_company == 2) {
            return '不可注册';
        } else {
            return '';
        }
    }

    /**
     * 说明: 可开发票中文
     *
     * @return string
     * @use open_bill_cn
     * @author 罗振
     */
    public function getOpenBillCnAttribute()
    {
        if ($this->open_bill == 1) {
            return '可开发票';
        } elseif ($this->open_bill == 2) {
            return '不可开发票';
        } else {
            return '';
        }
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


}
