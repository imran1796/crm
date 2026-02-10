<?php

namespace App\Services;

use App\Interfaces\BranchInterface;
use App\Interfaces\KanbanBoardRepositoryInterface;
use App\Interfaces\KanbanColumnRepositoryInterface;
use App\Interfaces\TaskRepositoryInterface;
use App\Models\Branch;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CompanyRepository;


class KanbanBoardService
{
    protected $boardRepo;
    protected $columnRepo;
    protected $taskRepo;
    public function __construct(
        KanbanBoardRepositoryInterface $boardRepo,
        KanbanColumnRepositoryInterface $columnRepo,
        TaskRepositoryInterface $taskRepo
    ) {
        $this->boardRepo = $boardRepo;
        $this->columnRepo = $columnRepo;
        $this->taskRepo = $taskRepo;
    }

    public function createBoard($data)
    {
        return $this->boardRepo->create($data);
    }

    public function addColumn($data)
    {
        return $this->columnRepo->create($data);
    }

    public function reorderColumns($boardId, $positions)
    {
        return $this->columnRepo->reorderColumns($boardId, $positions);
    }

    public function reorderTasks($columnId, $taskIds)
    {
        return $this->taskRepo->reorderTasks($columnId, $taskIds);
    }

    public function listBoards()
    {
        return $this->boardRepo->all();
    }
    public function find($id)
    {
        return $this->boardRepo->find($id);
    }

    public function loadFullBoard($board)
    {

        return $board->load([
            'columns' => function ($q) {
                $q->orderBy('position', 'asc');
            },
            'columns.tasks' => function ($q) {
                $q->orderBy('order', 'asc');
            }
        ]);
    }

    public function renameColumn($boardId, $columnId, $name)
    {
        try {
            return $this->columnRepo->renameColumn($boardId, $columnId, $name);
        } catch (\Exception $e) {
            \Log::error("KanbanBoardService renameColumn error: ".$e->getMessage());
            throw new \Exception("Failed to rename column.");
        }
    }


    public function updateBoardName($boardId, $name)
    {

        try {
            return $this->boardRepo->updateBoardName($boardId, $name);
        } catch (\Exception $e) {
            \Log::error("KanbanBoardService renameBoard error: ".$e->getMessage());
            throw new \Exception("Failed to rename Board.");
        }
    }

    public function deleteBoard($boardId)
    {

        try {
            $this->columnRepo->deleteColumn($boardId);
            $this->boardRepo->deleteBoard($boardId);
            return true;
        } catch (\Exception $e) {
            \Log::error("KanbanBoardService Delete Board error: ".$e->getMessage());
            throw new \Exception("Failed to Delete Board.");
        }
    }

    public function deleteColumn($boardId, $columnId)
    {
        try {
            return $this->columnRepo->deleteColumn($boardId, $columnId);
        } catch (\Exception $e) {
            \Log::error("KanbanBoardService Delete Column error: ".$e->getMessage());
            throw new \Exception("Failed to Delete Column.");
        }
    }




}
