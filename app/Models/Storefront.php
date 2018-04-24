<?php

namespace App\Models;

use App\User;

class Storefront extends BaseModel
{
    protected $appends = [
        'user_number'
    ];

    /**
     * 说明: 门店成员数量
     *
     * @return mixed
     * @use user_number
     * @author 罗振
     */
    public function getUserNumberAttribute()
    {
        return User::where('ascription_store', $this->id)->count();
    }
}
