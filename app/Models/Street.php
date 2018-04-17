<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Street extends BaseModel
{
    /**
     * 说明：所属区
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author jacklin\
     */
    public function area()
    {
        return $this->belongsTo('App\Models\Area');
    }
}
