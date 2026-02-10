<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class TaskController extends Controller
{
    protected $service;

    public function __construct(TaskService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->createTask($request->all())
        ]);
    }

    public function update(Request $request, $id)
    {
        Log::info($request->all());
        return response()->json([
            'success' => true,
            'data' => $this->service->updateTask($id, $request->all())
        ]);
    }

    public function destroy($id)
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->deleteTask($id)
        ]);
    }

    public function reorder(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->reorderTasks(
                $request->column_id,
                $request->task_ids
            )
        ]);
    }

    public function filter(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->filterTasks($request->all())
        ]);
    }

    public function boardTasks($boardId)
    {
        try {
            $tasks = $this->service->getTasksForBoard($boardId);

            return response()->json([
                'success' => true,
                'data' => $tasks
            ]);

        } catch (\Exception $e) {
            Log::error("Task BoardTasks Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to fetch tasks'], 500);
        }
    }

    public function move(Request $request)
    {
        Log::info($request->all());
        $request->validate([
            "task_id" => "required|integer",
            "from_column_id" => "required|integer",
            "to_column_id" => "required|integer",
            "new_position" => "required|integer"
        ]);

        try {
            $data = $this->service->moveTask(
                $request->task_id,
                $request->from_column_id,
                $request->to_column_id,
                $request->new_position
            );

            return response()->json([
                "success" => true,
                "message" => "Task moved successfully",
                "data" => $data
            ]);

        } catch (\Exception $e) {
            Log::error("TaskController@move error: ".$e->getMessage());

            return response()->json([
                "success" => false,
                "message" => "Failed to move task"
            ], 500);
        }
    }


}
