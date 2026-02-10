<?php

namespace App\Interfaces;

interface KanbanColumnRepositoryInterface
{
    public function create(array $data);
    public function reorderColumns($boardId, array $positions);
    public function renameColumn($boardId, $columnId, $name);

    public function deleteColumn($boardId, $columnId=null);


}
