<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HouseImgRecord extends Model
{
    protected $guarded = [];


    protected $casts = [
        'indoor_img' => 'array'
    ];

    protected $appends = [
        'status_cn', 'new_indoor_img_cn'
    ];

    public function officeBuildingHouse()
    {
        return $this->hasOne('App\Models\OfficeBuildingHouse', 'id', 'house_id');
    }

    public function user()
    {
        return $this->hasOne('App\User','id', 'user_id');
    }

    public function getStatusCnAttribute()
    {
        if ($this->status == 1) {
            return '审核中';
        } elseif($this->status == 2) {
            return '审核失败';
        } else {
            return '审核通过';
        }
    }

    /**
     * 说明: 图片url
     *
     * @return static
     * @use new_indoor_img_cn
     * @author 罗振
     */
    public function getNewIndoorImgCnAttribute()
    {
        return collect($this->indoor_img)->map(function ($img) {
            return [
                'name' => $img,
                'url' => config('setting.qiniu_url') . $img . config('setting.static')
            ];
        })->values();
    }

}
