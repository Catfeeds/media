<?php

namespace App\Repositories;

class StreetRepository extends BaseRepository
{

    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}