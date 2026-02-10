<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Exception;

class UserService
{
    protected $repo;

    public function __construct(UserRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function list()
    {
        try {
            return $this->repo->all();
        } catch (Exception $e) {
            Log::error('UserService list error: ' . $e->getMessage());
            throw $e;
        }
    }


    public function findById($id)
    {
        try {
            return $this->repo->find($id);
        } catch (Exception $e) {
            Log::error('UserService No Found error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function create(array $data)
    {
        try {
            return $this->repo->create($data);
        } catch (Exception $e) {
            Log::error('UserService create error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        try {
            return $this->repo->update($id, $data);
        } catch (Exception $e) {
            Log::error('UserService update error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            return $this->repo->delete($id);
        } catch (Exception $e) {
            Log::error('UserService delete error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createUserFromInstaller(array $data)
    {
        // Ensure the repository handles transactions and role assignment;
        // we pass 'roles' => ['Admin'] so repository assigns roles.
        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'status' => 1,
            'roles' => ['Admin'] // adjust role name as per your roles table
        ];

        return $this->repo->create($payload);
    }

}
