<?php

namespace App\Models;


class Custom extends BaseModel
{
    public function buildings()
    {
        return $this->hasMany('App\Models\CustomRelBuilding');
    }
}
