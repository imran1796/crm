<?php
namespace App\Services;

use App\Interfaces\ClientRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ClientService
{
    protected $repo;

    public function __construct(ClientRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function list(array $params = [])
    {
        $perPage = $params['per_page'] ?? 15;
        $filters = $params['filters'] ?? [];
        return $this->repo->paginate($perPage, $filters);
    }

    public function find(int $id)
    {
        return $this->repo->find($id);
    }

    public function create(array $data)
    {
        // Validate/normalize before calling repo if needed
        return $this->repo->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->repo->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->repo->delete($id);
    }

    public function search(string $term, array $params = [])
    {
        $perPage = $params['per_page'] ?? 15;
        return $this->repo->search($term, $perPage);
    }

    public function count()
    {
        return $this->repo->countAll();
    }

    public function findByEmail($email)
    {
        return $this->repo->findByEmail($email);
    }
}
