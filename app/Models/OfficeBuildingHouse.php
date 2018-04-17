<?php

namespace App\Models;

class OfficeBuildingHouse extends BaseModel
{
    protected $casts = [
        'owner_info' => 'array',
        'support_facilities' => 'array',
        'cost_detail' => 'array',
        'house_type_img' => 'array',
        'indoor_img' => 'array',
        'check_in_time' => 'date',
    ];

    protected $appends = ['renovation_cn', 'house_type', 'renting_style_cn', 'public_private_cn', 'house_busine_state_cn', 'payment_type_cn', 'orientation_cn', 'prospecting_cn', 'see_house_time_cn', 'house_proxy_type_cn', 'source_cn', 'certificate_type_cn', 'rent_price_unit_cn', 'pay_commission_unit_cn'];

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
            return '西北';
        } elseif ($this->orientation == 8) {
            return '南北';
        } elseif ($this->orientation == 9) {
            return '东西';
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
}
