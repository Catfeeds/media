<?php
/**
 * Created by PhpStorm.
 * User: 郭庆
 * Date: 2018/3/13
 * Time: 下午2:58
 */
return [
    'scopes' => [
        'place-orders' => 'Place orders',
        'check-status' => 'Check order status',
        '*' => 'all scopes',
    ],

    'default' => [
        'client_id' => env('CLIENT_ID'),
        'client_secret' => env('CLIENT_SECRET'),
        'scope' => '*',
    ],

    // 访问令牌有效期（天）
    'tokensExpireIn' => 15,

    // 刷新后的访问令牌有效期（天）
    'refreshTokensExpireIn' => 30,
];