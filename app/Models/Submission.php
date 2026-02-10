<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'form_id',
        'payload',
        'file_path',
        'is_read',
        'client_id'
    ];

    protected $casts = [
        'payload' => 'array',
        'is_read' => 'boolean'
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}

