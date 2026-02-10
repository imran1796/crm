<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KanbanBoard extends Model
{
    protected $fillable = ['name', 'created_by'];

    public function columns()
    {
        return $this->hasMany(KanbanColumn::class,'board_id')
            ->orderBy('position');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
 //
}
