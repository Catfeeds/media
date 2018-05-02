<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class OwnerViewRecord extends Model
{
    //
    protected $guarded = [];

    protected $appends = ['model_type','real_name'];

    /**
     * 说明: 房源model类型
     *
     * @return string
     * @author 罗振
     */
    public function getModelTypeAttribute()
    {
        if ($this->house_model == 'App\Models\DwellingHouse') {
            return 'residence';
        } elseif ($this->house_model == 'App\Models\OfficeBuildingHouse') {
            return 'officeBuild';
        } elseif ($this->house_model == 'App\Models\ShopsHouse') {
            return 'shopHouse';
        } else {
            return ;
        }
    }

    /**
     * 说明:查看记录用户名真实姓名
     *
     * @return mixed
     * @author 刘坤涛
     */
    public function getRealNameAttribute()
    {
        if ($this->user_id) {
            return User::where('id', $this->user_id)->first()->real_name;
        }
    }
}
