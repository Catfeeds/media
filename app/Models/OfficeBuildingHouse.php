<?php

namespace App\Models;

class OfficeBuildingHouse extends House
{
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
        'pay_commission' => 'float'
    ];

    protected $newAppends = [
        'renovation_cn', 'house_type', 'office_building_type_cn', 'public_private_cn',
        'house_busine_state_cn', 'payment_type_cn', 'split_cn', 'orientation_cn', 'prospecting_cn',
        'see_house_time_cn', 'house_proxy_type_cn', 'source_cn', 'certificate_type_cn',
        'rent_price_unit_cn', 'pay_commission_unit_cn', 'shortest_lease_cn', 'rent_free_cn',
        'house_type_img_cn', 'indoor_img_cn', 'building_name', 'register_company_cn', 'open_bill_cn'
    ];

    public function __construct()
    {
        $this->appends = array_merge($this->appends, $this->newAppends);
    }

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
     * 说明: 楼盘名
     *
     * @return mixed
     * @author 罗振
     */
    public function getBuildingNameAttribute()
    {
        if (empty($this->buildingBlock->building)) return ;

        return $this->buildingBlock->building->name;
    }

    /**
     * 说明: 装修中文
     *
     * @return string
     * @use renovation_cn
     * @author 罗振
     */
    public function getRenovationCnAttribute()
    {
        if ($this->renovation == 1) {
            return '豪华装修';
        } elseif ($this->renovation == 2) {
            return '精装修';
        } elseif ($this->renovation == 3) {
            return '中装修';
        } elseif ($this->renovation == 4) {
            return '间装修';
        } elseif ($this->renovation == 5) {
            return '毛坯';
        } else {
            return '装修情况异常';
        }
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
            return '写字楼类型异常';
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
            return '公私盘异常';
        }
    }

    /**
     * 说明: 房源业务状态中文
     *
     * @return string
     * @use house_busine_state_cn
     * @author 罗振
     */
    public function getHouseBusineStateCnAttribute()
    {
        if ($this->house_busine_state == 1) {
            return '有效';
        } elseif ($this->house_busine_state == 2) {
            return '暂缓';
        } elseif ($this->house_busine_state == 3) {
            return '已租';
        } elseif ($this->house_busine_state == 4) {
            return '收购';
        } elseif ($this->house_busine_state == 5) {
            return '托管';
        } elseif ($this->house_busine_state == 6) {
            return '无效';
        } else {
            return '房源业务状态异常';
        }
    }

    /**
     * 说明: 付佣单位转换
     *
     * @return string
     * @use pay_commission_unit_cn
     * @author 罗振
     */
    public function getPayCommissionUnitCnAttribute()
    {
        if ($this->pay_commission_unit == 1) {
            return '%';
        } elseif ($this->pay_commission_unit == 2) {
            return '元';
        } else {
            return '租金单位异常';
        }
    }

    /**
     * 说明: 支付方式中文
     *
     * @return string
     * @use payment_type_cn
     * @author 罗振
     */
    public function getPaymentTypeCnAttribute()
    {
        if ($this->payment_type == 1) {
            return '押一付一';
        } elseif ($this->payment_type == 2) {
            return '押一付二';
        } elseif ($this->payment_type == 3) {
            return '押一付三';
        } elseif ($this->payment_type == 4) {
            return '押二付一';
        } elseif ($this->payment_type == 5) {
            return '押二付二';
        } elseif ($this->payment_type == 6) {
            return '押二付三';
        } elseif ($this->payment_type == 7) {
            return '押三付一';
        } elseif ($this->payment_type == 8) {
            return '押三付二';
        } elseif ($this->payment_type == 9) {
            return '押三付三';
        } elseif ($this->payment_type == 10) {
            return '半年付';
        } elseif ($this->payment_type == 11) {
            return '年付';
        } elseif ($this->payment_type == 12) {
            return '面谈';
        } else {
            return '支付方式异常';
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
            return '拆分异常';
        }
    }

    /**
     * 说明: 朝向中文
     *
     * @return string
     * @use orientation_cn
     * @author 罗振
     */
    public function getOrientationCnAttribute()
    {
        if ($this->orientation == 1) {
            return '东';
        } elseif ($this->orientation == 2) {
            return '南';
        } elseif ($this->orientation == 3) {
            return '西';
        } elseif ($this->orientation == 4) {
            return '北';
        } elseif ($this->orientation == 5) {
            return '东南';
        } elseif ($this->orientation == 6) {
            return '东北';
        } elseif ($this->orientation == 7) {
            return '西南';
        } elseif ($this->orientation == 8) {
            return '西北';
        }  elseif ($this->orientation == 9) {
            return '东西';
        } elseif ($this->orientation == 10) {
            return '南北';
        } else {
            return '朝向异常';
        }
    }

    /**
     * 说明: 是否实勘中文
     *
     * @return string
     * @use prospecting_cn
     * @author 罗振
     */
    public function getProspectingCnAttribute()
    {
        if ($this->prospecting == 1) {
            return '是';
        } elseif ($this->prospecting == 2) {
            return '否';
        } else {
            return '是否实勘异常';
        }
    }

    /**
     * 说明: 看房时间中文
     *
     * @return string
     * @use see_house_time_cn
     * @author 罗振
     */
    public function getSeeHouseTimeCnAttribute()
    {
        if ($this->see_house_time == 1) {
            return '随时';
        } elseif ($this->see_house_time == 2) {
            return '非工作时间';
        } elseif ($this->see_house_time == 3) {
            return '电话预约';
        } else {
            return '看房时间异常';
        }
    }

    /**
     * 说明: 房源状态中文
     *
     * @return string
     * @use house_proxy_type_cn
     * @author 罗振
     */
    public function getHouseProxyTypeCnAttribute()
    {
        if ($this->house_proxy_type == 1) {
            return '独家';
        } elseif ($this->house_proxy_type == 2) {
            return '委托';
        } else {
            return '房源状态异常';
        }
    }

    /**
     * 说明: 来源中文
     *
     * @return string
     * @use source_cn
     * @author 罗振
     */
    public function getSourceCnAttribute()
    {
        if ($this->source == 1) {
            return '来电';
        } elseif ($this->source == 2) {
            return '来访';
        } elseif ($this->source == 3) {
            return '中介';
        } elseif ($this->source == 4) {
            return '友';
        } elseif ($this->source == 5) {
            return '告';
        } elseif ($this->source == 6) {
            return '街';
        } elseif ($this->source == 7) {
            return '网络';
        } else {
            return '来源异常';
        }
    }

    /**
     * 说明: 证件类型中文
     *
     * @return string
     * @use certificate_type_cn
     * @author 罗振
     */
    public function getCertificateTypeCnAttribute()
    {
        if ($this->certificate_type == 1) {
            return '房地产证';
        } elseif ($this->certificate_type == 2) {
            return '购房合同';
        } elseif ($this->certificate_type == 3) {
            return '购房发票';
        } elseif ($this->certificate_type == 4) {
            return '抵押合同';
        } elseif ($this->certificate_type == 5) {
            return '认购书';
        } elseif ($this->certificate_type == 6) {
            return '预售合同';
        } elseif ($this->certificate_type == 7) {
            return '回迁合同';
        } else {
            return '证件类型异常';
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
            return '租金单位异常';
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
            return '最短租期异常';
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
            return '免租期异常';
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
            return '公司是否可注册异常';
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
            return '是否可开发票异常';
        }
    }
}
