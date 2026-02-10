<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KanbanColumn extends Model
{
    protected $fillable = ['board_id', 'title', 'position'];

    public function board()
    {
        return $this->belongsTo(KanbanBoard::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class,'column_id')->orderBy('order');
    }
}
