<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrowseRecord extends Model
{
    protected $guarded = [];

    public function officeBuildingHouse()
    {
        return $this->belongsTo(OfficeBuildingHouse::class, 'house_id', 'id');
    }
}
