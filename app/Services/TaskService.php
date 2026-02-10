<?php

namespace App\Services;

use App\Interfaces\TaskRepositoryInterface;

class TaskService
{
    protected $repo;

    public function __construct(TaskRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function createTask($data)
    {
        return $this->repo->create($data);
    }

    public function updateTask($id, $data)
    {

        if (isset($data['status']) && $data['status'] === 'completed') {
            $data['is_completed'] = true;
        }else $data['is_completed'] = false;

        return $this->repo->update($id, $data);
    }

    public function deleteTask($id)
    {
        return $this->repo->delete($id);
    }

    public function reorderTasks($columnId, $ids)
    {
        return $this->repo->reorder($columnId, $ids);
    }

    public function filterTasks($filters)
    {
        return $this->repo->filter($filters);
    }

    public function getTasksForBoard($boardId)
    {
        return $this->repo->getTasksByBoard($boardId);
    }

    public function moveTask($taskId, $fromColumnId, $toColumnId, $newPosition)
    {

        return $this->repo->moveTask($taskId, $fromColumnId, $toColumnId, $newPosition);
    }

}
