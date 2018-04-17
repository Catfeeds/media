<?php

namespace App\Services;

use App\Models\DwellingHouse;
use App\Models\ShopsHouse;
use App\Repositories\DwellingHousesRepository;
use App\Repositories\OfficeBuildingHousesRepository;
use App\Repositories\ShopsHousesRepository;
use Illuminate\Support\Facades\DB;

class HousesService
{
    /**
     * 说明: 房源编号
     *
     * @param $initials
     * @param $houseId
     * @return string
     * @author 罗振
     */
    public function setHouseIdentifier($initials, $houseId)
    {
        if (strlen($houseId) == 1) {
            return $initials.date('Ymd', time()).'00'.$houseId;
        } elseif (strlen($houseId) == 2) {
            return $initials.date('Ymd', time()).'0'.$houseId;
        } else {
            return $initials.date('Ymd', time()).$houseId;
        }
    }
}