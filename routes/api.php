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

        // 住宅房源
        Route::resource('dwelling_houses', 'DwellingHousesController');

        // 商铺房源
        Route::resource('shops_houses', 'ShopsHousesController');

        // 写字楼房源
        Route::resource('office_building_houses', 'OfficeBuildingHousesController');


//    });

    /*
    |--------------------------------------------------------------------------
    | 楼盘管理
    |--------------------------------------------------------------------------
    */
    Route::resource('/buildings', 'BuildingController');
});


