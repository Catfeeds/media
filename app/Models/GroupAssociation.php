<?php

namespace App\Models;

class GroupAssociation extends BaseModel
{
    //
    public function user()
    {
        return $this->belongsTo('App\User', 'group_leader_id','id');
    }

}
