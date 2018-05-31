<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use EasyWeChat\Foundation\Application;

class WechatController extends Controller
{
    private $wechat;

    public function __construct(Application $application)
    {
        $this->wechat = $application;
    }


    public function index()
    {
        dd(123);
    }
}
