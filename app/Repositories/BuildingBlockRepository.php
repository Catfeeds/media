<?php

namespace App\Repositories;

use App\Models\BuildingBlock;

class BuildingBlockRepository extends BaseRepository
{

    private $model;

    public function model(BuildingBlock $model)
    {
        $this->model = $model;
    }

    public function add($request)
    {
        $this->model->create([
            '' => '',
            '' => '',
            '' => ''
        ]);
    }
}