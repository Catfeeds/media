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
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers:X-Token,Content-Type,Authorization');
header('Access-Control-Allow-Methods:*');

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

    /*
    |--------------------------------------------------------------------------
    | 楼盘管理
    |--------------------------------------------------------------------------
    */
    Route::resource('/buildings', 'BuildingController');
    // 楼盘下拉
    Route::get('/buildings_select', 'BuildingController@buildingSelect');

    /*
    |--------------------------------------------------------------------------
    | 楼座管理
    |--------------------------------------------------------------------------
    */
    Route::resource('/building_blocks', 'BuildingBlockController');
    // 所有楼座下拉数据
    Route::get('/building_blocks_all', 'BuildingBlockController@buildingBlocksSelect');

    /*
    |--------------------------------------------------------------------------
    | 城市管理
    |--------------------------------------------------------------------------
    */
    // 城市一级下拉数据
    Route::resource('/cities', 'CityController');
    Route::get('/cities_select', 'CityController@citiesSelect');

    /*
    |--------------------------------------------------------------------------
    | 区域管理
    |--------------------------------------------------------------------------
    */
    // 区域二级下拉数据
    Route::resource('/areas', 'AreaController');
    Route::get('/areas_select', 'AreaController@areasSelect');

    /*
    |--------------------------------------------------------------------------
    | 街道管理
    |--------------------------------------------------------------------------
    */
    Route::resource('/streets', 'StreetController');
    // 街道三级下拉数据
    Route::get('/streets_select', 'StreetController@streetsSelect');

    /*
    |--------------------------------------------------------------------------
    | 商圈管理
    |--------------------------------------------------------------------------
    */
    Route::resource('/blocks', 'BlockController');
    // 商圈三级下拉数据
    Route::get('/city_blocks', 'BlockController@cityBlocks');
    // 某区域下拉数据
    Route::get('/blocks_select', 'BlockController@blocksSelect');

    // 七牛token
    Route::resource('/qiniu', 'QiNiuController');
});


