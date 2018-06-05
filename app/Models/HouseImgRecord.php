<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HouseImgRecord extends Model
{
    protected $guarded = [];


    protected $casts = [
        'indoor_img' => 'array'
    ];


    public function officeBuildingHouse()
    {
        return $this->hasOne('App\Models\OfficeBuildingHouse', 'id', 'house_id');
    }

    public function user()
    {
        return $this->hasOne('App\User','id', 'user_id');
    }

}
