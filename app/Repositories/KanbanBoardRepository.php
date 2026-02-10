<?php

namespace App\Repositories;

use App\Interfaces\KanbanBoardRepositoryInterface;
use App\Models\KanbanBoard;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class KanbanBoardRepository implements KanbanBoardRepositoryInterface
{
    public function all()
    {
        return KanbanBoard::with('columns.tasks')->get();
    }

    public function find($id)
    {
        return KanbanBoard::with('columns.tasks')->findOrFail($id);
    }

    public function create(array $data)
    {
        try {
            DB::beginTransaction();
            $board = KanbanBoard::create($data);
            DB::commit();
            return $board;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Board Create Error: ".$e->getMessage());
            throw $e;
        }
    }

/*    public function update($id, array $data)
    {
        $board = KanbanBoard::findOrFail($id);
        $board->update($data);
        return $board;
    }*/


    public function updateBoardName($boardId, $name)
    { 
        try {
            \DB::beginTransaction();

            $board = KanbanBoard::findOrFail($boardId);
            $board->name = $name;
            $board->save();

            \DB::commit();
            return $board;

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error("Update Board Name Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteBoard($boardId)
    {
        try {
            \DB::beginTransaction();

            $board = KanbanBoard::findOrFail($boardId);


            // Delete columns first (CASCADE not always configured)
       /*     KanbanColumn::where('board_id', $boardId)->delete();

            // Delete tasks under those columns
            Task::whereIn('column_id', function ($query) use ($boardId) {
                $query->select('id')->from('kanban_columns')->where('board_id', $boardId);
            })->delete();*/

            $board->delete();

            \DB::commit();
            return true;

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error("Delete Board Error: " . $e->getMessage());
            throw $e;
        }
    }




}
