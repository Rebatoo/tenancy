<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Domain;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    // Define the domains relationship
    public function domains()
    {
        return $this->hasMany(Domain::class);
    }
}
