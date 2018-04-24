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
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers:X-Token,Content-Type,Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

//Route::group(['domain' => 'admin.agency.com', 'namespace' => 'API'], function () {
Route::group(['namespace' => 'API'], function () {

    // 登录
    Route::resource('login', 'LoginController');

    /*
    |--------------------------------------------------------------------------
    | 登录后的操作
    |--------------------------------------------------------------------------
    */
//    Route::group(['middleware' => ''], function () {

        // 退出登录
        Route::post('/logout', 'LoginController@logout');

        /*
        |--------------------------------------------------------------------------
        | 用户管理
        |--------------------------------------------------------------------------
        */
        Route::resource('users', 'UserController');
        //更改用户密码
        Route::post('update_password/{user}','UserController@updatePassword');
        //更新用户电话
        Route::post('update_tel/{user}','UserController@updateTel');
        // 获取所属门店
        Route::post('getStoreFrontsInfo','UserController@getStoreFrontsInfo');

    /*
    |--------------------------------------------------------------------------
    | 房源管理
    |--------------------------------------------------------------------------
    */
        // 住宅房源
        Route::resource('dwelling_houses', 'DwellingHousesController');
        //住宅房源业务状态
        Route::post('update_dwelling_business_state', 'DwellingHousesController@updateDwellingBusinessState');
        // 商铺房源
        Route::resource('shops_houses', 'ShopsHousesController');
        //商铺房源业务状态
        Route::post('update_shops_business_state', 'ShopsHousesController@updateShopsBusinessState');
        // 写字楼房源
        Route::resource('office_building_houses', 'OfficeBuildingHousesController');
        // 写字楼房源业务状态修改
        Route::post('update_office_business_state', 'OfficeBuildingHousesController@updateOfficeBusinessState');

        /*
        |--------------------------------------------------------------------------
        | 跟进
        |--------------------------------------------------------------------------
        */
        Route::resource('tracks', 'TracksController');
//    });

    /*
    |--------------------------------------------------------------------------
    | 楼盘管理
    |--------------------------------------------------------------------------
    */
    Route::resource('/buildings', 'BuildingController');
    // 楼盘下拉
    Route::get('/buildings_select', 'BuildingController@buildingSelect');
    // 某区下的所有楼楼盘
    Route::get('/area_buildings', 'BuildingController@areaBuildings');
    // 楼盘搜索下拉
    Route::get('/building_search_select', 'BuildingController@buildingSearchSelect');


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
    Route::get('/areas_of_city', 'AreaController@areasOfCity');


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
    Route::post('/custom_status/{custom}', 'CustomController@updateStatus');

    // 七牛token
    Route::resource('/qiniu', 'QiNiuController');

    /*
    |--------------------------------------------------------------------------
    | 门店管理
    |--------------------------------------------------------------------------
    */
    Route::resource('storefronts', 'StorefrontsController');

    /*
    |--------------------------------------------------------------------------
    | 权限管理
    |--------------------------------------------------------------------------
    */
    Route::resource('permissions', 'PermissionsController');

    /*
    |--------------------------------------------------------------------------
    | 角色管理
    |--------------------------------------------------------------------------
    */
    Route::resource('roles', 'RolesController');


    /*
    |--------------------------------------------------------------------------
    | element下拉格式数据
    |--------------------------------------------------------------------------
    */
    Route::get('/select_buildings', 'SelectDataController@areaBuildings');
    Route::get('/select_building_blocks', 'SelectDataController@buildingBlocks');
    Route::get('/select_block_houses', 'SelectDataController@blockHouses');
    Route::get('/select_customs', 'SelectDataController@selectCustoms');
});


