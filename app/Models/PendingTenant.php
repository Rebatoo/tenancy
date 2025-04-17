<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PendingTenant extends Authenticatable
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

    public function getAuthIdentifierName()
    {
        return 'email';
    }

    public function getAuthIdentifier()
    {
        return $this->email;
    }
}
