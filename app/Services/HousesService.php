<?php

namespace App\Services;

use App\Repositories\DwellingHousesRepository;
use App\Repositories\OfficeBuildingHousesRepository;
use App\Repositories\ShopsHousesRepository;
use Illuminate\Support\Facades\DB;

class HousesService
{
    public function getAllTypeHouse()
    {
        $one = DB::table('dwelling_houses')->select('id');


        $two = DB::table('shops_houses')->select('id')->union($one);

        $all = DB::table('office_building_houses')->select('id')->union($two);

        dd($all);
    }

}