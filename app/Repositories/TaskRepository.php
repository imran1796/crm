<?php

namespace App\Repositories;

use App\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class TaskRepository implements TaskRepositoryInterface
{
    public function create(array $data)
    {
        try {
            DB::beginTransaction();
            $task = Task::create($data);
            DB::commit();
            return $task;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Task Create Error: ".$e->getMessage());
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        try {
            DB::beginTransaction();
            $task = Task::findOrFail($id);
            // if status = completed → auto mark is_completed = true


            $task->update($data);
            DB::commit();
            return $task;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Task Update Error: ".$e->getMessage());
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            Task::findOrFail($id)->delete();
            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Task Delete Error: ".$e->getMessage());
            throw $e;
        }
    }

    public function reorderTasks($columnId, array $orderedIds)
    {
        try {
            DB::beginTransaction();

            foreach ($orderedIds as $position => $taskId) {
                Task::where('id', $taskId)
                    ->update(['column_id' => $columnId, 'position' => $position]);
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Task reorder error: ".$e->getMessage());
            throw $e;
        }
    }

    public function filter(array $filters)
    {
        $query = Task::query();

        if (!empty($filters['client_id'])) {
            $query->where('client_id', $filters['client_id']);
        }

        if (!empty($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        if (!empty($filters['due_date'])) {
            $query->whereDate('due_date', $filters['due_date']);
        }

        return $query->get();
    }
    public function countTasksForToday()
    {
        $today = now()->format('Y-m-d');
        return Task::whereDate('due_date', $today)->count();
    }

    public function getTasksByBoard($boardId)
    {
        return Task::whereHas('column', function ($q) use ($boardId) {
            $q->where('board_id', $boardId);
        })
            ->with(['column' => function ($q) {
                $q->select('id', 'title', 'board_id');
            }])
            ->orderBy('order', 'asc')
            ->get();
    }

    public function moveTask($taskId, $fromColumnId, $toColumnId, $newPosition)
    {
        try {
            DB::beginTransaction();

            $task = Task::where("id", $taskId)
                ->where("column_id", $fromColumnId)
                ->firstOrFail();

            // Update task column
            $task->column_id = $toColumnId;
            $task->order = $newPosition;
            $task->save();

            // Reorder target column tasks
            $tasks = Task::where("column_id", $toColumnId)
                ->orderBy("order")
                ->get();

            $position = 0;
            foreach ($tasks as $t) {
                $t->order = $position++;
                $t->save();
            }

            // Reorder old column tasks
            $oldTasks = Task::where("column_id", $fromColumnId)
                ->orderBy("order")
                ->get();

            $pos = 0;
            foreach ($oldTasks as $ot) {
                $ot->order = $pos++;
                $ot->save();
            }

            DB::commit();
            return $task;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Move Task Error: ".$e->getMessage());
            throw $e;
        }
    }
    public function moveToBoard($taskId, $targetBoardId, $targetColumnId)
    {
        try {
            DB::beginTransaction();

            $task = Task::lockForUpdate()->findOrFail($taskId);

            // Get last position in target column
            $lastPosition = Task::where('column_id', $targetColumnId)
                ->max('position');

            $task->update([
                'board_id' => $targetBoardId,
                'column_id' => $targetColumnId,
                'position' => ($lastPosition ?? 0) + 1,
            ]);

            DB::commit();
            return $task;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Task move repository error: ".$e->getMessage());
            throw $e;
        }
    }




}
