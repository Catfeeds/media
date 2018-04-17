<?php

namespace App\Models;

class Area extends BaseModel
{
    /**
     * 说明：所属城市
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author jacklin
     */
    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }
}
