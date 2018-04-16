<?php

namespace App\Repositories;

class AreaRepository extends BaseRepository
{

    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}