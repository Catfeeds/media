<?php

namespace App;

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
    protected $casts = [
        'ascription_store' => 'array',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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
}
