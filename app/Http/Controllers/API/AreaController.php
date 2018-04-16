<?php

namespace App\Http\Controllers\API;

use App\Models\Area;
use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AreaController extends Controller
{
    public function index(Request $request)
    {
        $city = City::all();
        dd($city);
    }
    public function store(Request $request)
    {
        Area::create([
            'name' => $request->name,
            'city_id' => $request->city_id
        ]);
    }
}
