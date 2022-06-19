<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

Abstract class Repository
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getRelatedAttributes(String $params): void
    {
        $this->model = $this->model->with($params);
    }

    public function filter(String $filters): void
    {
        $filters = explode('&', $filters);
        foreach ($filters as $condition) {
            $filter = explode(':', $condition);
            $this->model = $this->model->where($filter[0], $filter[1], $filter[2]);
        }
    }

    public function selectAttributes(String $params): void
    {
        $this->model = $this->model->selectRaw($params);
    }

    public function getResult()
    {
        return $this->model->get();
    }
}
