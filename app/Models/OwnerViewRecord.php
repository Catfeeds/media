<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OwnerViewRecord extends Model
{
    //
    protected $guarded = [];

    protected $appends = ['model_type'];

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
}
