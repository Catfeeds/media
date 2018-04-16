<?php

namespace App\Http\Controllers\API;

use App\Models\Street;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StreetController extends Controller
{
    public function store(Request $request)
    {
        Street::create([
            'name' => $request->name,
            'area_id' => $request->area_id
        ]);
    }
}
