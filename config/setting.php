<?php

return [
    /*
    |--------------------------------------------------------------------------
    | 七牛管理
    |--------------------------------------------------------------------------
    */
    // 七牛
    'qiniu_access_key' => 'c_M1yo7k90djYAgDst93NM3hLOz1XqYIKYhaNJZ4', // 七牛访问KEY
    'qiniu_secret_key' => 'Gb2K_HZbepbu-A45y646sP1NNZF3AqzY_w680d5h', // 七牛访问秘钥

    // 开发 七牛存储空间
    'qiniu_bucket' => env('QINIU_BUCKET'),     // 七牛访问url
    'qiniu_url' => env('QINIU_URL'),
    //慢查询请求地址
    'http_url'  => env('HTTP_URL'),

    /*
    |--------------------------------------------------------------------------
    | 房源,客户放入公盘时间
    |--------------------------------------------------------------------------
    */
    'house_to_public' => 5,
    'custom_to_public' => 7,

    'agency_host' => env('AGENCY_HOST'),
    'host' => env('HOST'),

    // 楼盘默认图片
    'building_default_img' => 'https://cdn.chulouwang.com/app/imgs/building_none.jpg',
    // 房源默认图
    'house_default_img' => 'https://cdn.chulouwang.com/app/imgs/house_none.jpg'
];