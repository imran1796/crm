<?php

namespace App\Interfaces;

interface KanbanBoardRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
   // public function update($id, array $data);
    public function updateBoardName($boardId, $name);

    public function deleteBoard($boardId);




}
