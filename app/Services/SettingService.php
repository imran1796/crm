<?php

namespace App\Services;

use App\Interfaces\SettingRepositoryInterface;

class SettingService
{
    protected $repo;

    public function __construct(SettingRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getAll()
    {
        return $this->repo->getAll();
    }

    public function update(array $data)
    {
        return $this->repo->updateMany($data);
    }
}
