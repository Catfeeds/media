<?php

namespace App\Traits;

trait HouseTraits{

    /**
     * 说明: 维护人
     *
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     * @author 罗振
     */
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'guardian')->first();
    }

    /**
     * 说明: 经纪人中文
     *
     * @return string
     * @use guardian_cn
     * @author 罗振
     */
    public function getGuardianCnAttribute()
    {
        if (!empty($this->user())) {
            return $this->user()->real_name;
        } else {
            return '';
        }
    }

    /**
     * 说明: 门店中文
     *
     * @return string
     * @use storefronts_cn
     * @author 罗振
     */
    public function getStorefrontsCnAttribute()
    {
        if (!empty($this->user())) {
            if (!empty($this->user()->ascription_store)) {
                return $this->user()->storefront->storefront_name;
            } else {
                return '';
            }
        }
    }

    /**
     * 说明: 最后跟进时间
     *
     * @return string
     * @use tracks_time
     * @author 罗振
     */
    public function getTracksTimeAttribute()
    {
        if (empty($this->tracks())) {
            return '';
        } else {
            return $this->tracks()->created_at->format('m-d');
        }
    }

    /**
     * 说明: 跟进
     *
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     * @author 罗振
     */
    public function tracks()
    {
        return $this->hasMany('App\Models\Track','house_id', 'id')->orderBy('id', 'desc')->first();
    }

    /**
     * 说明: 入住时间中文
     *
     * @return string
     * @use check_in_time_cn
     * @author 罗振
     */
    public function getCheckInTimeCnAttribute()
    {
        if (empty($this->check_in_time)) {
            return '';
        } else {
            return $this->check_in_time->format('Y-m-d');
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
            return '朋友';
        } elseif ($this->source == 5) {
            return '广告';
        } elseif ($this->source == 6) {
            return '扫街';
        } elseif ($this->source == 7) {
            return '网络';
        } else {
            return '';
        }
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
            return '';
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
            return '';
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
            return '';
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
            return '';
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
            return '';
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
            return '';
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
            return '';
        }
    }

    /**
     * 说明：房号
     *
     * @return string
     * @author jacklin
     */
    public function getHouseNumberInfoAttribute()
    {
        if (empty($this->buildingBlock)) return '';
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

    /**
     * 说明: 楼盘名
     *
     * @return mixed
     * @use building_name
     * @author 罗振
     */
    public function getBuildingNameAttribute()
    {
        if (empty($this->buildingBlock->building)) return ;

        return $this->buildingBlock->building->name;
    }

    /**
     * 说明: 佣金
     *
     * @return string
     * @use pay_commission_cn
     * @author 罗振
     */
    public function getPayCommissionCnAttribute()
    {
        if (empty($this->pay_commission)) {
            return '';
        } else {
            if ($this->pay_commission_unit == 1) {
                return $this->pay_commission.'%';
            } else {
                return $this->pay_commission.'元';
            }
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
            return '';
        }
    }

    /**
     * 说明: 面积
     *
     * @return string
     * @use constru_acreage_cn
     * @author 罗振
     */
    public function getConstruAcreageCnAttribute()
    {
        if (empty($this->constru_acreage)) {
            return '';
        } else {
            return $this->constru_acreage.'㎡';
        }
    }

}