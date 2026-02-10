<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class GenericRepository
{
    /**
     * Get all records for a model with optional relations.
     */
    public function getAll(string $modelName, array $with = [], int $limit = PHP_INT_MAX)
    {
        $modelClass = $this->resolveModel($modelName);

        return $modelClass::with($with)->latest()->take($limit)->get();
    }

    /**
     * Get records with conditions.
     */
    public function getWhere(string $modelName, array $where = [], array $with = [], int $limit = PHP_INT_MAX)
    {
        $modelClass = $this->resolveModel($modelName);

        $query = $modelClass::with($with)->latest()->take($limit);

        foreach ($where as $column => $value) {
            if (is_array($value)) {
                $query->whereIn($column, $value);
            } else {
                $query->where($column, $value);
            }
        }

        return $query->get();
    }

    /**
     * Resolve model class from string.
     */
    private function resolveModel(string $modelName)
    {
        $modelClass = "App\\Models\\$modelName";

        if (!class_exists($modelClass)) {
            throw new ModelNotFoundException("Model [$modelName] does not exist.");
        }

        return new $modelClass;
    }
}
