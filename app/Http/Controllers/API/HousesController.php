<?php
namespace App\Http\Controllers\API;

use App\Repositories\DwellingHousesRepository;
use App\Repositories\OfficeBuildingHousesRepository;
use App\Repositories\ShopsHousesRepository;
use App\Services\HousesService;
use Illuminate\Http\Request;

class HousesController extends APIBaseController
{
    public function index(
        Request $request,
        HousesService $housesService
//        ShopsHousesRepository $shopsHousesRepository,   // 商铺房源
//        DwellingHousesRepository $dwellingHousesRepository, // 住宅房源
//        OfficeBuildingHousesRepository $officeBuildingHousesRepository  // 写字楼房源
    )
    {
        $housesService->getAllTypeHouse();

        return '房源控制器';
    }
}