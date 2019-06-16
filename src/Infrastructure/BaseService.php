<?php

namespace Niyam\ACL\Infrastructure;

class BaseService extends BaseEntity
{
    protected $model;

    function __construct($model = null)
    {
        $this->model = $model;
    }

    public function findEntitiesWhereRaw($whereRaw, $columns = '*', $with = null)
    {
        if ($with)
            return $this->model->with($with)->whereRaw($whereRaw)->get($columns);

        return $this->model->whereRaw($whereRaw)->get($columns);
    }
}
