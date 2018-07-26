<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class RawCustom extends Model
{
    protected $guarded = [];
    protected $connection = 'mysql';

    protected $appends = ['source_cn', 'demand_cn', 'valid_cn', 'clinch_cn'];

    //店长关联user表
    public function shopkeeperUser()
    {
        return $this->belongsTo(User::class,'shopkeeper_id', 'id');
    }

    //业务员关联user表
    public function staffUser()
    {
        return $this->belongsTo(User::class,'staff_id', 'id');
    }

    //关联客源
    public function custom()
    {
        return $this->hasOne(Custom::class,'identifier', 'identifier');
    }

    public function house()
    {
        return $this->hasOne(OfficeBuildingHouse::class,'gd_identifier', 'identifier');
    }

    public function getSourceCnAttribute()
    {
        switch ($this->source) {
            case 1:
                return '400电话';
                break;
            case 2:
                return '官网客服';
                break;
            case 3:
                return '百度信息流';
            case 4:
                return '今日头条信息流';
                break;
            case 5:
                return 'app';
                break;
            case 6:
                return 'PC';
                break;
            case 7:
                return '微信';
            case 8:
                return '小程序';
            case 9:
                return '58同城';
                break;
                default;
                break;
        }
    }

    public function getDemandCnAttribute()
    {
        switch ($this->demand) {
            case 1:
                return '投放房源';
                break;
            case 2:
                return '委托找房';
                break;
            case 3:
                return '企业服务';
                break;
            case 4:
                return '其他';
                default;
                break;
        }
    }

    //工单状态
    public function getValidCnAttribute()
    {
        if ($this->valid == 1) {
            return '有效';
        } else {
            return '无效';
        }
    }
    
    //工单是否成交
    public function getClinchCnAttribute()
    {
        if ($this->clinch == 1) {
            return '成交';
        } else {
            return '未成交';
        }
    }

}
