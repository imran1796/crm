<?php
namespace App\Services;

use App\Interfaces\PermissionRepositoryInterface;

class PermissionService
{
    protected $repo;

    public function __construct(PermissionRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function list()
    {
        return $this->repo->all();
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function update($id, array $data)
    {
        return $this->repo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    public function groupedPermissions()
    {
        return $this->repo->groupedByPrefix();
    }
}
