<?php

namespace App\Repositories;

use App\Models\Building;

class BuildingRepository extends BaseRepository
{

    private $model;

    public function model(Building $model)
    {
        $this->model = $model;
    }

    public function add($request)
    {
        return $this->model()->create([
            'name' => 'test'
        ]);
    }
}