<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingTenant extends Model
{
    protected $fillable = [
        'name',
        'email',
        'location',
        'domain',
        'contact_number',
        'approved',
    ];

    protected $casts = [
        'approved' => 'boolean',
    ];
}
