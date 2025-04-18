<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Domain;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\DatabaseConfig;
use Stancl\Tenancy\Database\DatabaseManager;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    protected $fillable = [
        'id',

        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    // Define the domains relationship
    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    public function getDatabaseName(): string
    {
        if (empty($this->company_name)) {
            throw new \RuntimeException('The company_name attribute must be set to generate the database name.');
        }
        return $this->company_name;
    }

    public function getDatabaseConnectionName(): string
    {
        return 'tenant';
    }

    public function database(): DatabaseConfig
    {
        return new DatabaseConfig($this); // Pass the current Tenant instance
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tenant) {
            if (!isset($tenant->data)) {
                $tenant->data = [];
            }
            if (empty($tenant->company_name)) {
                throw new \RuntimeException('The company_name attribute must be set when creating a tenant.');
            }
        });
    }

    public function getTenancyDbNameAttribute()
    {
        return $this->attributes['tenancy_db_name'] ?? null;
    }

    public function setTenancyDbNameAttribute($value)
    {
        $this->attributes['tenancy_db_name'] = $value;
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['tenancy_db_name'] = $this->tenancy_db_name;
        return $array;
    }
}
