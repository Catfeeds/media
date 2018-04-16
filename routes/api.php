<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


//Route::group(['domain' => 'admin.agency.com', 'namespace' => 'API'], function () {
Route::group(['namespace' => 'API'], function () {
    /*
    |--------------------------------------------------------------------------
    | 登录后的操作
    |--------------------------------------------------------------------------
    */
//    Route::group(['middleware' => ''], function () {

        Route::get('/test', function () {
            return '123';
        });


        Route::resource('dwelling_houses', 'DwellingHousesController');


        /*
        |--------------------------------------------------------------------------
        | 租户
        |--------------------------------------------------------------------------
        */
        Route::resource('user', 'UserController');
        // 租户 我的设置
        Route::get('/userSetting', 'UserController@userSetting');
        // 修改用户姓名
        Route::get('/updateRealName', 'UserController@updateRealName');
        // 修改用户图像
        Route::post('/updateUserHeaderPic', 'UserController@updateUserHeaderPic');

//    });
});


