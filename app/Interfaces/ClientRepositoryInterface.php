<?php

namespace App\Interfaces;

interface ClientRepositoryInterface
{

    public function paginate(int $perPage = 15, array $filters = []);

    public function find(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function search(string $term, int $perPage = 15);
    public function countAll();

    public function findByEmail(string $email);

}
