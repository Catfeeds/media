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

    protected $appends = [
        'level_cn'
    ];

    // , 'storefront_name'

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
            return '总经理';
        } elseif ($this->level == 2) {
            return '区域经理';
        } elseif ($this->level == 3) {
            return '店长';
        } elseif ($this->level == 4) {
            return '业务员';
        } else {
            return '成员级别异常';
        }
    }

//    /**
//     * 说明: 所属门店
//     *
//     * @return string
//     * @use storefront_name
//     * @author 罗振
//     */
//    public function getStorefrontNameAttribute()
//    {
//        if ($this->level == 1) {
//            return ;
//        }
//
//        if ($this->level == 2) {
//            return implode(',', Storefront::where('area_manager_id', $this->id)->pluck('storefront_name')->toArray());
//        } else {
//            return Storefront::where('id', $this->ascription_store)->first()->storefront_name;
//        }
//    }
}
