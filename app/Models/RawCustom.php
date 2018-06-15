<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class RawCustom extends Model
{
    protected $guarded = [];
    protected $connection = 'mysql';

    protected $appends = ['source_cn', 'demand_cn'];

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

    //关联工单hao
    public function custom()
    {
        return $this->hasOne(Custom::class,'identifier', 'identifier');
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
                default;
                break;
        }
    }

}
