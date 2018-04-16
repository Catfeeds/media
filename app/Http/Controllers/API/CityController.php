<?php

namespace App\Http\Controllers\API;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CityController extends Controller
{
    public function store(Request $request)
    {
        City::create([
            'name' => $request->name
        ]);
    }
}
