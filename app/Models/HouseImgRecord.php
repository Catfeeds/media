<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HouseImgRecord extends Model
{
    protected $guarded = [];


    protected $casts = [
        'indoor_img' => 'array'
    ];


}
