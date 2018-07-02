<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers:X-Token,Content-Type,Authorization,safeString');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

Route::resource('/logs', 'LogController');
//Route::group(['domain' => 'admin.agency.com', 'namespace' => 'API'], function () {
Route::group(['namespace' => 'API'], function () {
    // 登录
    Route::resource('login', 'LoginController');

    Route::group(['middleware' => 'safe.validate'], function () {
        /*
        |--------------------------------------------------------------------------
        | clw平台权限管理操作成功运行系统命令
        |--------------------------------------------------------------------------
        */
        Route::get('/run_command', 'HomePagesController@runCommand');

        /*
        |--------------------------------------------------------------------------
        | element下拉格式数据
        |--------------------------------------------------------------------------
        */
        Route::get('/select_buildings', 'SelectDataController@areaBuildings');
        Route::get('/select_building_blocks', 'SelectDataController@buildingBlocks');
        // 某区域下拉数据
        Route::get('/blocks_select', 'BlockController@blocksSelect');
        // 楼盘下拉
        Route::get('/buildings_select', 'BuildingController@buildingSelect');
        // 楼盘搜索下拉
        Route::get('/building_search_select', 'BuildingController@buildingSearchSelect');
        Route::get('/cities_select', 'CityController@citiesSelect');
        Route::get('/areas_select', 'AreaController@areasSelect');

        // 更新房源照片
        Route::get('/house_img_update/{token}', 'HousesController@houseImgUpdateView');
        // 更新房源照片操作
        Route::post('/house_img_update', 'HousesController@houseImgUpdate');
        // 生成二维码
        Route::get('/make_qr_code', 'HousesController@makeQrCode');

        // 七牛token
        Route::resource('/qiniu', 'QiNiuController');

    });

    /*
    |--------------------------------------------------------------------------
    | 城市管理
    |--------------------------------------------------------------------------
    */
    // 城市一级下拉数据
    Route::resource('/cities', 'CityController');

    /*
    |--------------------------------------------------------------------------
    | 区域管理
    |--------------------------------------------------------------------------
    */
    // 区域二级下拉数据
    Route::resource('/areas', 'AreaController');
    Route::get('/areas_of_city', 'AreaController@areasOfCity');


    /*
    |--------------------------------------------------------------------------
    | 商圈管理
    |--------------------------------------------------------------------------
    */
    Route::resource('/blocks', 'BlockController');
    // 商圈三级下拉数据
    Route::get('/city_blocks', 'BlockController@cityBlocks');





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
    Route::get('/select_block_houses', 'SelectDataController@blockHouses');
    Route::get('/select_customs', 'SelectDataController@selectCustoms');

    /*
    |--------------------------------------------------------------------------
    | 后台首页管理
    |--------------------------------------------------------------------------
    */
    Route::resource('home_page', 'HomePagesController');
    //待跟进房源数据
    Route::get('wait_track_house', 'HomePagesController@waitTrackHouse');
    //待跟进客户数据
    Route::get('wait_track_customer', 'HomePagesController@waitTrackCustomer');
    //写字楼统或客户计数据
    Route::get('statistic_data', 'HomePagesController@statisticData');
    //获取环比数据
    Route::get('get_ring_than_data', 'HomePagesController@getRingThanData');
    //根据本周或者本月获取房源、客户X轴数据
    Route::get('get_chart_data', 'HomePagesController@getChartData');
    //根据登录人级别获取对应业务员数据
    Route::get('get_salesman_data', 'HomePagesController@getSalesmanData');

    //客服工单
    Route::resource('raw_custom', 'RawCustomsController');
    //获取店长信息
    Route::get('get_shopkeeper', 'RawCustomsController@getShopkeeper');
    //获取店长下属业务员
    Route::get('get_staff', 'RawCustomsController@getStaff');
    //店长分配工单
    Route::post('distribution', 'RawCustomsController@distribution');
    //业务员确定工单
    Route::post('determine', 'RawCustomsController@determine');
    //店长处理页面
    Route::get('shopkeeper_list', 'RawCustomsController@shopkeeperList');
    //业务员处理页面
    Route::get('staff_list', 'RawCustomsController@staffList');

    /*
    |--------------------------------------------------------------------------
    | 登录后的操作
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => ['auth:api', 'token_invalid']], function () {

        // 退出登录
        Route::post('/logout', 'LoginController@logout');

        /*
        |--------------------------------------------------------------------------
        | 用户管理
        |--------------------------------------------------------------------------
        */
        Route::resource('users', 'UserController');
        //更改用户密码
        Route::post('update_password', 'UserController@updatePassword');
        //更新用户电话
        Route::post('update_tel/{user}', 'UserController@updateTel');
        // 获取所属门店
        Route::post('get_storefronts_info', 'UserController@getStorefrontsInfo');

        // 业务统计
        Route::post('business_statistics','UserController@businessStatistics');

        // 获取下级成员
        Route::get('get_subordinate_user', 'UserController@getSubordinateUser');
        // 获取门店下门店经理(组长)
        Route::get('get_group_and_storefronts', 'UserController@getGroupAndStorefronts');

        /*
        |--------------------------------------------------------------------------
        | 房源管理
        |--------------------------------------------------------------------------
        */
        // 住宅房源
        Route::resource('dwelling_houses', 'DwellingHousesController');
        Route::get('my_dwelling_houses_list', 'DwellingHousesController@myDwellingHousesList');
        // 住宅房源业务状态
        Route::post('update_dwelling_business_state', 'DwellingHousesController@updateDwellingBusinessState');
        Route::post('get_owner_info', 'DwellingHousesController@getOwnerInfo');
        // 商铺房源
        Route::resource('shops_houses', 'ShopsHousesController');
        Route::get('my_shops_houses_list', 'ShopsHousesController@myShopsHousesList');
        // 商铺房源业务状态
        Route::post('update_shops_business_state', 'ShopsHousesController@updateShopsBusinessState');
        // 写字楼房源
        Route::resource('office_building_houses', 'OfficeBuildingHousesController');

        // 写字楼房源业务状态修改
        Route::post('update_office_business_state', 'OfficeBuildingHousesController@updateOfficeBusinessState');
        // 上线房源
        Route::post('update_shelf', 'OfficeBuildingHousesController@updateShelf');

        // 三个房源获取业主信息和查看记录
        Route::get('get_owner_info', 'HousesController@getOwnerInfo');

        // 房号验证
        Route::post('room_number_validate','HousesController@roomNumberValidate');

        // 修改房源图片审核列表
        Route::get('house_img_auditing', 'HousesController@houseImgAuditing');
        Route::get('house_img_auditing_details/{id}', 'HousesController@houseImgAuditingDetails');
        Route::post('auditing_operation', 'HousesController@auditingOperation');

        /*
        |--------------------------------------------------------------------------
        | 跟进
        |--------------------------------------------------------------------------
        */
        Route::resource('tracks', 'TracksController');
        Route::get('get', 'TracksController@get');
        Route::get('customs_tracks_list', 'TracksController@customsTracksList');
        Route::post('add_customs_tracks', 'TracksController@addCustomsTracks');

        /*
        |--------------------------------------------------------------------------
        | 楼盘管理
        |--------------------------------------------------------------------------
        */
        Route::resource('/buildings', 'BuildingController');
        // 某区下的所有楼楼盘
        Route::get('/area_buildings', 'BuildingController@areaBuildings');

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
        | 房源查看记录
        |--------------------------------------------------------------------------
        */
        Route::resource('owner_view_records', 'OwnerViewRecordsController');

        /*
        |--------------------------------------------------------------------------
        | 门店管理
        |--------------------------------------------------------------------------
        */
        Route::resource('storefronts', 'StorefrontsController');
        Route::get('get_all_storefronts_info', 'StorefrontsController@getAllStorefrontsInfo');

        /*
        |--------------------------------------------------------------------------
        | 客户管理
        |--------------------------------------------------------------------------
        */
        Route::resource('/customs', 'CustomController');
        Route::post('/custom_status/{custom}', 'CustomController@updateStatus');
        Route::get('my_custom_list', 'CustomController@myCustomList');

    });

});
