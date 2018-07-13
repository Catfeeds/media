<?php

namespace App\Models;


class Block extends BaseModel
{
    //
    public function area()
    {
        return $this->belongsTo('App\Models\Area','area_id','id');
    }

    public function building()
    {
        return $this->hasMany('App\Models\Building','block_id', 'id');
    }
}
