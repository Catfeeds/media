<?php

namespace App\Models;


use App\User;

class Custom extends BaseModel
{
    protected $casts = [
        'other' => 'array'
    ];
    protected $appends = [
        'status_label', 'status_label', 'class_label', 'source_label',
        'belong_label', 'pay_type_label', 'commission_label', 'need_type_label',
        'need_type_info', 'area_label', 'building_label', 'acre_label', 'room_label',
        'floor_label', 'reno_label', 'orien_label', 'guardian_name'
    ];

    /**
     * 说明：客户意向楼盘
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author jacklin
     */
    public function buildings()
    {
        return $this->hasMany('App\Models\CustomRelBuilding');
    }

    /**
     * 说明：意向区域
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author jacklin
     */
    public function area()
    {
        return $this->belongsTo('App\Models\Area');
    }

    public function guardianData()
    {
        return $this->belongsTo(User::class, 'guardian', 'id');
    }

    /**
     * 说明：客源状态
     *
     * @return string
     * @author jacklin
     */
    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case 1:
                return '有效';
            case 2:
                return '暂缓';
            case 3:
                return '成交';
            case 4:
                return '无效';
        }
    }

    /**
     * 说明：客户等级
     *
     * @return string
     * @author jacklin
     */
    public function getClassLabelAttribute()
    {
        switch ($this->status) {
            case 1:
                return 'A';
            case 2:
                return 'B';
            case 3:
                return 'C';
        }
    }

    /**
     * 说明：客户等级
     *
     * @return string
     * @author jacklin
     */
    public function getSourceLabelAttribute()
    {
        switch ($this->source) {
            case 1:
                return '来电';
            case 2:
                return '来访';
            case 3:
                return '中介';
            case 4:
                return '朋友';
            case 5:
                return '广告';
            case 6:
                return '扫街';
            case 7:
                return '网络';
        }
    }

    /**
     * 说明：公盘私盘
     *
     * @return string
     * @author jacklin
     */
    public function getBelongLabelAttribute()
    {
        switch ($this->belong) {
            case 1:
                return '公盘';
            case 2:
                return '私盘';
        }
    }

    /**
     * 说明：付款方式
     *
     * @return string
     * @author jacklin
     */
    public function getPayTypeLabelAttribute()
    {
        switch ($this->payment_type) {
            case 1:
                return '押一付一';
            case 2:
                return '押一付二';
            case 3:
                return '押一付三';
            case 4:
                return '押二付一';
            case 5:
                return '押二付二';
            case 6:
                return '押二付三';
            case 7:
                return '押三付一';
            case 8:
                return '押三付二';
            case 9:
                return '押三付三';
            case 10:
                return '半年付';
            case 11:
                return '年付';
            case 12:
                return '面谈';
        }
    }

    /**
     * 说明：付佣
     *
     * @return string
     * @author jacklin
     */
    public function getCommissionLabelAttribute()
    {
        switch ($this->pay_commission_unit) {
            case 1:
                return (int)$this->pay_commission . '%';
            case 2:
                return $this->pay_commission . '元';
        }
    }

    /**
     * 说明：求租类型
     *
     * @return string
     * @author jacklin
     */
    public function getNeedTypeLabelAttribute()
    {
        switch ($this->need_type) {
            case 1:
                return '商铺';
            case 2:
                return '住宅';
            case 3:
                return '写字楼';
        }
    }

    /**
     * 说明：求租类型
     *
     * @return string
     * @author jacklin
     */
    public function getNeedTypeInfoAttribute()
    {
        switch ($this->need_type) {
            case 1:
                switch ($this->renting_style) {
                    case 1: return '整租';
                    case 2: return '合租';
                }
            case 2:
                switch ($this->office_building_type) {
                    case 1: return '纯写字楼';
                    case 2: return '商住楼';
                    case 3: return '商业综合体楼';
                    case 4: return '酒店写字楼';
                    case 5: return '其他';
                }
            case 3:
                switch ($this->shops_type) {
                    case 1: return '住宅底商';
                    case 2: return '商业街商铺';
                    case 3: return '酒店商底';
                    case 4: return '社区商铺';
                    case 5: return '沿街商铺';
                    case 6: return '写字底商';
                    case 7: return '购物中心';
                    case 8: return '旅游商铺';
                    case 9: return '其他';
                }
        }
    }

    /**
     * 说明：意向区
     *
     * @return mixed
     * @author jacklin
     */
    public function getAreaLabelAttribute()
    {
        if (!empty($this->area)) return $this->area->name;
    }

    /**
     * 说明：意向楼盘信息
     *
     * @return string
     * @author jacklin
     */
    public function getBuildingLabelAttribute()
    {
        $buildingsId = $this->buildings->pluck('id')->toArray();
        $names = Building::find($buildingsId)->pluck('name')->toArray();
        return implode(',', $names);
    }

    /**
     * 说明：需求面积
     *
     * @return string
     * @author jacklin
     */
    public function getAcreLabelAttribute()
    {
        $str = '';
        if (!empty($this->acre_low)) $str.=$this->acre_low . '㎡-';
        if (!empty($this->acre_high)) $str.=$this->acre_high . '㎡';
        return $str;
    }

    /**
     * 说明：需求户型
     *
     * @return string
     * @author jacklin
     */
    public function getRoomLabelAttribute()
    {
        $str = '';
        if (!empty($this->room)) $str .= $this->room . '室';
        if (!empty($this->hall)) $str .= $this->hall . '厅';
        if (!empty($this->toilet)) $str .= $this->toilet . '卫';
        if (!empty($this->kitchen)) $str .= $this->kitchen . '厨';
        if (!empty($this->balcony)) $str .= $this->balcony . '阳台';
        return $str;
    }

    /**
     * 说明：需求楼层
     *
     * @return string
     * @author jacklin
     */
    public function getFloorLabelAttribute()
    {
        $str = '';
        if (!empty($this->floor_low)) $str .= $this->floor_low . '层-';
        if (!empty($this->floor_high)) $str .= $this->floor_high . '层';
        return $str;
    }

    /**
     * 说明：需求装修
     *
     * @return string
     * @author jacklin
     */
    public function getRenoLabelAttribute()
    {
        switch ($this->renovation) {
            case 1: return '豪华装修';
            case 2: return '精装修';
            case 3: return '中等装修';
            case 4: return '简装修';
            case 5: return '毛坯';
        }
    }

    /**
     * 说明：需求朝向
     *
     * @return string
     * @author jacklin
     */
    public function getOrienLabelAttribute()
    {
        switch ($this->renovation) {
            case 1: return '东';
            case 2: return '南';
            case 3: return '西';
            case 4: return '北';
            case 5: return '东南';
            case 6: return '西南';
            case 7: return '东北';
            case 8: return '西北';
        }
    }

    public function getGuardianNameAttribute()
    {
        if (empty($this->guardianData)) return '';
        return $this->guardianData->real_name;
    }
}
