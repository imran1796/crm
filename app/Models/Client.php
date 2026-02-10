<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{

    protected $fillable = [
        'client_number',
        'name',
        'surname',
        'company',
        'role',
        'phone',
        'email',
        'street',
        'postal_code',
        'city',
        'country',
        'assigned_to',
        'custom_fields'
    ];

    protected $casts = [
        'custom_fields' => 'array'
    ];

    // Relationships
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

   /* public function tasks()
    {
        return $this->hasMany(Task::class);
    }*/

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
