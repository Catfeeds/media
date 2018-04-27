<?php

namespace App\Models;


use App\User;

class Track extends BaseModel
{
    protected $casts = [
        'tracks_time' => 'array',

    ];

    protected $appends = ['tracks_mode_label','custom_name','user_name'];

    public function custom()
    {
        return $this->belongsTo(Custom::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

//    public function house()
//    {
//        \Log::info($this->house_model);
//            if (empty($this->house_model)) return collect();
//            return $this->belongsTo($this->house_model,'house_id','id');
//
//
////        $model = new $this->house_model;
////        dd($model);
////        dd($this->house_model === "App\Models\DwellingHouse");
//    }

    public function getTracksModeLabelAttribute()
    {
        switch ($this->tracks_mode) {
            case 1:
                return '实勘';
            case 2:
                return '电话';
            case 3:
                return '短信';
            case 4:
                return '来访';
            case 5:
                return '拜访';
            case 6:
                return '约看';
            case 7:
                return '看房';
            case 8:
                return '签约';
            case 9:
                return '拿钥匙';
            case 10:
                return '续约';
            case 11:
                return '重激活';
             default;
                break;
        }
    }

    public function getCustomNameAttribute()
    {
        if (empty($this->custom)) return '';
        return $this->custom->name;
    }

    public function getUserNameAttribute()
    {
        return $this->user->real_name;

    }

//    public function getHouseNameAttribute()
//    {
//        if (empty($this->house)) return'';
//        return $this->house;
//    }

}
