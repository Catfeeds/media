<?php

namespace App\Models;


use App\User;

class Track extends BaseModel
{
    protected $casts = [
        'tracks_time' => 'array',

    ];

    protected $appends = ['tracks_mode_label','custom_name','user_name'];

    /**
     * 说明:获取客户信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author 刘坤涛
     */
    public function custom()
    {
        return $this->belongsTo(Custom::class);
    }

    /**
     * 说明:获取跟进人信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author 刘坤涛
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 说明:匹配跟进方式
     *
     * @return string
     * @author 刘坤涛
     */
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

    /**
     * 说明:获取客户姓名
     *
     * @return string
     * @author 刘坤涛
     */
    public function getCustomNameAttribute()
    {
        if (empty($this->custom)) return '';
        return $this->custom->name;
    }

    /**
     * 说明:获取跟进人姓名
     *
     * @return mixed
     * @author 刘坤涛
     */
    public function getUserNameAttribute()
    {
        return $this->user->real_name;
    }

}
