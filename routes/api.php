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

        // 住宅房源
        Route::resource('dwelling_houses', 'DwellingHousesController');

        // 商铺房源
        Route::resource('shops_houses', 'ShopsHousesController');

        // 写字楼房源
        Route::resource('office_building_houses', 'OfficeBuildingHousesController');

        // 房源
        Route::resource('houses', 'HousesController');

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
    Route::post('/change_name_unit/{building_block}', 'BuildingBlockController@changeNameUnit');
    Route::post('/add_name_unit', 'BuildingBlockController@addNameUnit');
    Route::post('/add_block_info/{building_block}', 'BuildingBlockController@addBlockInfo');
    Route::get('/building_blocks_list', 'BuildingBlockController@allBlocks');

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

    /*
    |--------------------------------------------------------------------------
    | 客户管理
    |--------------------------------------------------------------------------
    */
    Route::resource('/customs', 'CustomController');

    // 七牛token
    Route::resource('/qiniu', 'QiNiuController');
});


