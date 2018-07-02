<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaPermissionGroup extends Model
{
    protected $table = 'permission_groups';

    protected $guarded = [];

    public function permission()
    {
        return $this->hasMany('App\Models\Permission', 'group_id','id');
    }

}
