<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Street extends BaseModel
{
    public function area()
    {
        return $this->belongsTo('App\Models\Area');
    }
}
