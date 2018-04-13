<?php

namespace App\Repositories;

class TestRepository extends BaseRepository
{

    private $model;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(Model $model)
    {
        $this->model = $model;
    }
}