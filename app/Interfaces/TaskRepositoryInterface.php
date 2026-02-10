<?php

namespace App\Interfaces;

interface TaskRepositoryInterface
{
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function reorderTasks($columnId, array $orderedIds);
    public function countTasksForToday();
    public function getTasksByBoard($boardId);
    public function moveTask($taskId, $fromColumnId, $toColumnId, $newPosition);
    public function moveToBoard($taskId, $targetBoardId, $targetColumnId);


}
