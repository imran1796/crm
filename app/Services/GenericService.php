<?php

namespace App\Services;

use App\Repositories\GenericRepository;

class GenericService
{
    protected $repository;

    public function __construct(GenericRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all records (optionally with relations).
     */
    public function getData(string $modelName, array $with = [], int $limit = PHP_INT_MAX)
    {
        return $this->repository->getAll($modelName, $with, $limit);
    }

    /**
     * Get filtered data.
     */
    public function getDataWhere(string $modelName, array $where = [], array $with = [], int $limit = PHP_INT_MAX)
    {
        return $this->repository->getWhere($modelName, $where, $with, $limit);
    }
}
