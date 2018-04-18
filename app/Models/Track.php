<?php

namespace App\Models;


class Track extends BaseModel
{
    protected $casts = [
        'tracks_time' => 'date',
    ];
}
