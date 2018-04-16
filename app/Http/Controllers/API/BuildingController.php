<?php

namespace App\Http\Controllers\API;

use App\Repositories\BuildingRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BuildingController extends APIBaseController
{
    public function store(Request $request, BuildingRepository $repository)
    {
        $res = $repository->add($request);
        return $this->sendResponse($res, 200);
    }
}
