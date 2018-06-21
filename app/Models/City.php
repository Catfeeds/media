<?php

namespace App\Models;


class City extends BaseModel
{
    //
    public function area()
    {
        return $this->hasMany('App\Models\Area','city_id','id');
    }
}
