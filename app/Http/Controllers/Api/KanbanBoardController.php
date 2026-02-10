<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\KanbanBoardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KanbanBoardController extends Controller
{
    protected $service;

    public function __construct(KanbanBoardService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->listBoards()
        ]);
    }

    public function store(Request $request)
    {
        $data = [
            'name' => $request->name,
            'created_by' => auth()->id()
        ];

        return response()->json([
            'success' => true,
            'data' => $this->service->createBoard($data)
        ]);
    }

    public function addColumn(Request $request, $boardId)
    {
        Log::info($request->all());

        return response()->json([
            'success' => true,
            'data' => $this->service->addColumn([
                'board_id' => $boardId,
                'title' => $request->title,
                'position' => $request->position ?? 0
            ])
        ]);
    }

    public function reorderColumns(Request $request, $boardId)
    {
        return response()->json([
            'success' => true,
            'message' => 'Columns reordered',
            'data' => $this->service->reorderColumns($boardId, $request->positions)
        ]);
    }

    public function show($id)
    {
        try {
            $board = $this->service->find($id);


            if (!$board) {
                return response()->json(['success' => false, 'message' => 'Board not found'], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $this->service->loadFullBoard($board)
            ]);

        } catch (\Exception $e) {
            Log::error("KanbanBoard Show Error: " . $e->getMessage() . $e->getFile() . $e->getLine());
            return response()->json(['success' => false, 'message' => 'Error loading board'], 500);
        }
    }

    public function renameColumn(
        Request $request,
                $boardId,
                $columnId
    )
    {
        Log::info($request->all(), ['boardId' => $boardId, 'columnId' => $columnId]);
        try {
            $column = $this->service->renameColumn(
                $boardId,
                $columnId,
                $request->title
            );

            return response()->json([
                'success' => true,
                'message' => 'Column renamed successfully',
                'data' => $column,
            ]);
        } catch (\Exception $e) {
            \Log::error("KanbanBoardController renameColumn error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to rename column'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    { 
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        try {
            $board = $this->service->updateBoardName($id, $request->name);

            return response()->json([
                'success' => true,
                'message' => 'Board updated successfully',
                'data' => $board
            ]);

        } catch (\Exception $e) {
            \Log::error("KanbanBoardController@update error: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update board'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->deleteBoard($id);

            return response()->json([
                'success' => true,
                'message' => 'Board deleted successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error("KanbanBoardController@destroy error: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete board'
            ], 500);
        }
    }

    public function deleteColumn($boardId, $columnId)
    {
        try {
            $this->service->deleteColumn($boardId, $columnId);

            return response()->json([
                'success' => true,
                'message' => 'Column deleted successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error("KanbanBoardController@deleteColumn error: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete column'
            ], 500);
        }
    }




}
