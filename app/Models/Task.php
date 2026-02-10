<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'board_id', 'column_id', 'client_id',
        'assigned_to', 'title', 'description',
        'due_date', 'order', 'is_completed','priority','status'
    ];

    public function board()
    {
        return $this->belongsTo(KanbanBoard::class,'board_id');
    }

    public function column()
    {
        return $this->belongsTo(KanbanColumn::class, 'column_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
