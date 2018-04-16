<?php

namespace App\Repositories;

use App\Models\Building;

class BuildingRepository extends Building
{

    private $model;

    public function __construct(Building $model)
    {
        $this->model = $model;
    }

    public function add($request)
    {
        return $this->model->create([
            'name' => 'test'
        ]);
    }
}