<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Domain;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\DatabaseConfig;
use Stancl\Tenancy\Database\DatabaseManager;
use Illuminate\Support\Facades\Log;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    protected $fillable = [
        'id',
        'data',
        'company_name', // Add company_name to fillable attributes
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
            $data = $tenant->data ?? []; // Retrieve the data attribute or initialize it as an empty array

            if (empty($data['company_name'])) { // Check company_name in the data array
                $data['company_name'] = $tenant->id; // Fallback to tenant ID if company_name is missing
            }

            if (empty($data['company_name'])) {
                throw new \RuntimeException('The company_name attribute must be set when creating a tenant.');
            }

            $tenant->data = $data; // Set the modified data back to the attribute

            Log::info('Creating tenant', ['tenant' => $tenant->toArray()]);
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
