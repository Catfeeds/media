<?php

namespace App;

use App\Models\Storefront;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles;

    // 不允许集体赋值的字段
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

//    protected $appends = [
//        'level_cn', 'store_name'
//    ];
    protected $appends = [
        'level_cn'
    ];

    public function storefront()
    {
        return $this->belongsTo(Storefront::class, 'ascription_store', 'id');
    }

    /**
     * 说明: 获取token
     *
     * @return mixed
     * @author 罗振
     */
    public function getAccessTokenAttribute()
    {
        $token = $this->token();
        return $token->id;
    }

    /**
     * 说明: 自定义授权用户名（默认为手机号）
     *
     * @param $username
     * @return mixed
     * @author 罗振
     */
    public function findForPassport($username)
    {
        return User::where('tel', $username)->first();
    }

    /**
     * 说明: 当前登录用户信息
     *
     * @return mixed
     * @author 罗振
     */
    public function userInfo()
    {
        return Auth::guard('api')->user();
    }

    /**
     * 说明: 成员级别中文
     *
     * @return string
     * @use level_cn
     * @author 罗振
     */
    public function getLevelCnAttribute()
    {
        if ($this->level == 1) {
            return '市场总监';
        } elseif ($this->level == 2) {
            return '区域经理';
        } elseif ($this->level == 3) {
            return '商圈经理';
        } elseif ($this->level == 4) {
            return '业务经理';
        } elseif ($this->level == 5) {
            return '门店经理';
        }
    }

    /**
     * 说明: 所属门店
     *
     * @return string
     * @use store_name
     * @author 罗振
     */
    public function getStoreNameAttribute()
    {
        if (empty($this->ascription_store)) return '';
        return $this->storefront->storefront_name;
    }
}
