<?php

namespace App\Repositories;

use App\Interfaces\KanbanColumnRepositoryInterface;
use App\Models\KanbanColumn;
use App\Models\Task;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class KanbanColumnRepository implements KanbanColumnRepositoryInterface
{
    public function create(array $data)
    {

        Log::info($data);

        try {
            DB::beginTransaction();
            $column = KanbanColumn::create($data);
            DB::commit();
            return $column;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Kanban Column Create Error: ".$e->getMessage());
            throw $e;
        }
    }

    public function reorderColumns($boardId, array $positions)
    {
        try {
            DB::beginTransaction();

            foreach ($positions as $position => $columnId) {
                KanbanColumn::where('id', $columnId)
                    ->where('board_id', $boardId)
                    ->update(['position' => $position]);
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Column Reorder Error: ".$e->getMessage());
            throw $e;
        }



    }

    public function renameColumn($boardId, $columnId, $name)
    {
        \DB::beginTransaction();
        try {
            $column = KanbanColumn::where('board_id', $boardId)
                ->where('id', $columnId)
                ->firstOrFail();

            $column->title = $name;
            $column->save();

            \DB::commit();
            return $column;
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error("Kanban renameColumn error: ".$e->getMessage());
            throw $e;
        }
    }

    public function deleteColumn($boardId, $columnId=null)
    {
        Log::info("Kanban Delete Column Id: ".$columnId." BoardId: ".$boardId);
        try {
            \DB::beginTransaction();
            if($columnId==null){
                Task::where('board_id', $boardId)->delete();
                KanbanColumn::where('board_id', $boardId)->delete();
            }else{
                $column = KanbanColumn::where('id', $columnId)
                    ->where('board_id', $boardId)
                    ->firstOrFail();

                // Delete tasks first
                Task::where('column_id', $columnId)->delete();

                // Delete the column
                $column->delete();
            }

            \DB::commit();
            return true;

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error("Delete Column Error: " . $e->getMessage());
            throw $e;
        }
    }



}
