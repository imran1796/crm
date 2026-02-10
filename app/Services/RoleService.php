<?php
namespace App\Services;

use App\Interfaces\RoleRepositoryInterface;

class RoleService
{
    protected $repo;

    public function __construct(RoleRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function list()
    {
        return $this->repo->all();
    }

    public function paginate($perPage = 20)
    {
        return $this->repo->paginate($perPage);
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

    public function getRolePermissions($id)
    {
        return $this->repo->getRolePermissions($id);
    }
}
