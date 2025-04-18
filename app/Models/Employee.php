<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'role',
        'email',
        'phone',
        'address',
        'gender',
        'birthdate',
        'start_date',
        'status',
        'shift',
        'photo'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'start_date' => 'date',
    ];
} 