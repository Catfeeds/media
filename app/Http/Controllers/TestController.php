<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test2(Request $request)
    {
        \Log::info($request->data);
        \Log::info(json_decode($request->data, true));
    }
}
