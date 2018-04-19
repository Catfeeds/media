<?php

namespace App\Models;

class DwellingHouse extends BaseModel
{
    protected $casts = [
        'owner_info' => 'array',
        'feature_lable' => 'array',
        'support_facilities' => 'array',
        'cost_detail' => 'array',
        'house_type_img' => 'array',
        'indoor_img' => 'array',
        'check_in_time' => 'date',
    ];

    protected $appends = ['renovation_cn', 'house_type', 'renting_style_cn', 'public_private_cn', 'house_busine_state_cn', 'payment_type_cn', 'orientation_cn', 'prospecting_cn', 'see_house_time_cn', 'house_proxy_type_cn', 'source_cn', 'certificate_type_cn', 'pay_commission_unit_cn', 'shortest_lease_cn','house_type_img_cn', 'indoor_img_cn'];

    /**
     * 说明: 关联楼座
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author 罗振
     */
    public function buildingBlock()
    {
        return $this->hasOne(BuildingBlock::class, 'id', 'building_blocks_id');
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

        if (!empty($this->toilet)) {
            $houseType = $houseType.$this->toilet.'卫';
        }

        if (!empty($this->kitchen)) {
            $houseType = $houseType.$this->kitchen.'厨';
        }

        if (!empty($this->balcony)) {
            $houseType = $houseType.$this->balcony.'阳台';
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
            return '出租方式异常';
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
            return '西南';
        } elseif ($this->orientation == 7) {
            return '东北';
        } elseif ($this->orientation == 8) {
            return '西北';
        }  else {
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
            return '最短租期异常';
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
            return config('setting.qiniu_url') . $img . config('setting.static');
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
            return config('setting.qiniu_url') . $img . config('setting.static');
        })->values();
    }
}
