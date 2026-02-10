<?php

namespace App\Interfaces;

interface BranchInterface
{
    public function getAllBranches();
    public function getBranchById($id);
    public function createBranch(array $data);
    public function updateBranch($id,array $data);
    public function deleteBranch($id);
}
