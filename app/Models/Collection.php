<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $guarded = [];

    protected $connection = 'mysql';

    /**
     * 说明: 关联房源
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author 刘坤涛
     */
    public function officeBuildingHouse()
    {
        return $this->belongsTo(OfficeBuildingHouse::class, 'house_id', 'id');
    }
}
