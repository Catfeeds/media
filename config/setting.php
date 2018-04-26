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
    'qiniu_bucket' => 'louwang-test',
    'qiniu_url' => 'http://osibaji20.bkt.clouddn.com/',               // 七牛访问url

    // 线上 七牛存储空间
//    'qiniu_bucket' => 'chulouwang-upload',
//    'qiniu_url' => 'https://upload.chulouwang.com/',               // 七牛访问url


    /*
    |--------------------------------------------------------------------------
    | 房源,客户放入公盘时间
    |--------------------------------------------------------------------------
    */
    'house_to_public' => 30,
    'custom_to_public' => 30,

];