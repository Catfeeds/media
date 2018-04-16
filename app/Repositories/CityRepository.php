<?php

namespace App\Repositories;

class CityRepository extends BaseRepository
{

    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}